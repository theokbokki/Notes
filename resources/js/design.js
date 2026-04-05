import "./bootstrap";

class InfiniteMasonry {
    constructor(container, { breakpoint = 1020 } = {}) {
        this.container = container;
        this.breakpoint = breakpoint;
        this.active = false;
        this.templates = [];
        this.slots = [];
        this.nodePoolsByTemplate = [];
        this.headIndex = 0;
        this.tailIndex = 0;
        this.columns = 1;
        this.columnWidth = 0;
        this.gap = 8;
        this.columnBottoms = [];
        this.columnTops = [];
        this.scroller = null;
        this.startOffset = 100000;
        this.wasAboveBreakpoint = false;

        this.handleScroll = this.handleScroll.bind(this);
        this.handleResize = this.handleResize.bind(this);

        this.waitForAllImages().then(() => this.initialize());
    }

    waitForAllImages() {
        const images = this.container.querySelectorAll("img");

        return Promise.all(Array.from(images).map((image) => {
            if (image.complete) {
                return Promise.resolve();
            }

            return new Promise((resolve) => {
                image.addEventListener("load", resolve, { once: true });
                image.addEventListener("error", resolve, { once: true });
            });
        }));
    }

    initialize() {
        this.originalElements = Array.from(this.container.children);
        this.templates = this.measureTemplates();
        this.nodePoolsByTemplate = this.templates.map(() => []);
        this.wasAboveBreakpoint = this.isAboveBreakpoint();
        this.activate();
        window.addEventListener("resize", this.handleResize);
    }

    measureTemplates() {
        return Array.from(this.container.querySelectorAll("img")).map((image) => ({
            source: image.src,
            aspectRatio: image.naturalHeight / image.naturalWidth || 1,
        }));
    }

    preCreateNodes() {
        const copiesPerTemplate = 20;

        for (let templateIndex = 0; templateIndex < this.templates.length; templateIndex++) {
            for (let copy = 0; copy < copiesPerTemplate; copy++) {
                const node = document.createElement("img");

                node.className = "design__image";
                node.style.position = "absolute";
                node.style.display = "none";
                node.src = this.templates[templateIndex].source;

                this.container.appendChild(node);
                this.nodePoolsByTemplate[templateIndex].push(node);
            }
        }
    }

    activate() {
        if (this.active) return;

        this.active = true;
        this.readLayoutFromContainer();
        this.hideOriginalElements();
        this.applyMasonryStyles();

        if (this.isAboveBreakpoint()) {
            this.createScrollContainer();
            this.preCreateNodes();
            this.buildInitialContent();
        } else {
            this.layoutStaticMasonry();
            this.updateContainerHeight();
            this.applyPositions();
        }
    }

    buildInitialContent() {
        const viewportHeight = this.scroller.clientHeight;
        this.columnBottoms = new Array(this.columns).fill(this.startOffset);
        this.columnTops = new Array(this.columns).fill(this.startOffset);
        this.fillDownward(this.startOffset + viewportHeight * 4);
        this.fillUpward(this.startOffset - viewportHeight * 4);
        this.updateContainerHeight();
        this.applyPositions();
        this.scroller.scrollTop = this.startOffset;
    }

    readLayoutFromContainer() {
        this.container.style.display = "";
        this.container.style.position = "";
        this.gap = parseFloat(getComputedStyle(this.container).gap) || 8;
        this.columns = this.readColumnCount();
    }

    hideOriginalElements() {
        this.originalElements.forEach((element) => (element.style.display = "none"));
    }

    applyMasonryStyles() {
        this.container.style.position = "relative";
        this.container.style.display = "block";
        this.container.style.overflowAnchor = "none";
        this.columnWidth = this.calculateColumnWidth();
        this.columnBottoms = new Array(this.columns).fill(0);
        this.columnTops = new Array(this.columns).fill(0);
    }

    isAboveBreakpoint() {
        return window.innerWidth >= this.breakpoint;
    }

    createScrollContainer() {
        if (this.scroller) return;

        this.scroller = document.createElement("div");
        this.scroller.style.cssText = "position:fixed;inset:0;overflow-y:auto;overflow-anchor:none;";

        const designWrapper = this.container.closest(".design");
        designWrapper.parentNode.insertBefore(this.scroller, designWrapper);

        this.scroller.appendChild(designWrapper);
        this.scroller.addEventListener("scroll", this.handleScroll, { passive: true });
    }

    removeScrollContainer() {
        if (!this.scroller) return;

        this.scroller.removeEventListener("scroll", this.handleScroll);

        const designWrapper = this.container.closest(".design");

        this.scroller.parentNode.insertBefore(designWrapper, this.scroller);
        this.scroller.remove();
        this.scroller = null;
    }

    readColumnCount() {
        const value = getComputedStyle(this.container).getPropertyValue("grid-template-columns");

        if (!value || value === "none") return 1;

        return value.split(" ").length;
    }

    calculateColumnWidth() {
        return (this.container.offsetWidth - this.gap * (this.columns - 1)) / this.columns;
    }

    findShortestColumn(heights) {
        let shortest = 0;

        for (let index = 1; index < heights.length; index++) {
            if (heights[index] < heights[shortest]) shortest = index;
        }

        return shortest;
    }

    findTallestColumn(heights) {
        let tallest = 0;

        for (let index = 1; index < heights.length; index++) {
            if (heights[index] > heights[tallest]) tallest = index;
        }

        return tallest;
    }

    horizontalOffsetForColumn(column) {
        return column * (this.columnWidth + this.gap);
    }

    templateIndexFor(slotIndex) {
        const length = this.templates.length;

        return ((slotIndex % length) + length) % length;
    }

    slotHeightFor(slotIndex) {
        return this.templates[this.templateIndexFor(slotIndex)].aspectRatio * this.columnWidth;
    }

    acquireNodeForTemplate(templateIndex) {
        const pool = this.nodePoolsByTemplate[templateIndex];
        const node = pool.pop();

        if (!node) return null;

        node.style.display = "";
        node.style.width = `${this.columnWidth}px`;

        return node;
    }

    releaseNodeToPool(node, templateIndex) {
        node.style.display = "none";

        this.nodePoolsByTemplate[templateIndex].push(node);
    }

    layoutStaticMasonry() {
        for (let index = 0; index < this.templates.length; index++) {
            const column = this.findShortestColumn(this.columnBottoms);
            const height = this.slotHeightFor(index);
            const verticalOffset = this.columnBottoms[column];
            const horizontalOffset = this.horizontalOffsetForColumn(column);
            const templateIndex = this.templateIndexFor(index);
            const node = this.acquireNodeForTemplate(templateIndex);

            if (!node) {
                const newNode = document.createElement("img");

                newNode.className = "design__image";
                newNode.style.position = "absolute";
                newNode.src = this.templates[templateIndex].source;
                newNode.style.width = `${this.columnWidth}px`;

                this.container.appendChild(newNode);
                this.slots.push({ node: newNode, column, horizontalOffset, verticalOffset, height, index, templateIndex });
            } else {
                this.slots.push({ node, column, horizontalOffset, verticalOffset, height, index, templateIndex });
            }

            this.columnBottoms[column] = verticalOffset + height + this.gap;
            this.tailIndex++;
        }
    }

    fillDownward(untilVerticalOffset) {
        while (Math.min(...this.columnBottoms) < untilVerticalOffset) {
            const index = this.tailIndex;
            const column = this.findShortestColumn(this.columnBottoms);
            const height = this.slotHeightFor(index);
            const verticalOffset = this.columnBottoms[column];
            const horizontalOffset = this.horizontalOffsetForColumn(column);
            const templateIndex = this.templateIndexFor(index);
            const node = this.acquireNodeForTemplate(templateIndex);

            if (!node) return;

            this.slots.push({ node, column, horizontalOffset, verticalOffset, height, index, templateIndex });
            this.columnBottoms[column] = verticalOffset + height + this.gap;
            this.tailIndex++;
        }
    }

    fillUpward(untilVerticalOffset) {
        while (Math.max(...this.columnTops) > untilVerticalOffset) {
            const nextHeadIndex = this.headIndex - 1;
            const column = this.findTallestColumn(this.columnTops);
            const height = this.slotHeightFor(nextHeadIndex);
            const verticalOffset = this.columnTops[column] - this.gap - height;
            const horizontalOffset = this.horizontalOffsetForColumn(column);
            const templateIndex = this.templateIndexFor(nextHeadIndex);
            const node = this.acquireNodeForTemplate(templateIndex);

            if (!node) return;

            this.headIndex = nextHeadIndex;
            this.slots.unshift({ node, column, horizontalOffset, verticalOffset, height, index: this.headIndex, templateIndex });
            this.columnTops[column] = verticalOffset;
        }
    }

    applyPositions() {
        for (const slot of this.slots) {
            slot.node.style.transform = `translate(${slot.horizontalOffset}px, ${slot.verticalOffset}px)`;
        }
    }

    updateContainerHeight() {
        this.container.style.height = `${Math.max(...this.columnBottoms)}px`;
    }

    recalculateColumnTops() {
        this.columnTops = new Array(this.columns).fill(Infinity);

        for (const slot of this.slots) {
            this.columnTops[slot.column] = Math.min(this.columnTops[slot.column], slot.verticalOffset);
        }

        for (let column = 0; column < this.columns; column++) {
            if (this.columnTops[column] === Infinity) this.columnTops[column] = 0;
        }
    }

    recalculateColumnBottoms() {
        this.columnBottoms = new Array(this.columns).fill(0);

        for (const slot of this.slots) {
            this.columnBottoms[slot.column] = Math.max(this.columnBottoms[slot.column], slot.verticalOffset + slot.height + this.gap);
        }
    }

    releaseAllSlots() {
        for (const slot of this.slots) this.releaseNodeToPool(slot.node, slot.templateIndex);

        this.slots = [];
        this.headIndex = 0;
        this.tailIndex = 0;
    }

    resetToMiddle() {
        this.releaseAllSlots();

        this.columnBottoms = new Array(this.columns).fill(0);
        this.columnTops = new Array(this.columns).fill(0);

        this.buildInitialContent();
    }

    handleScroll() {
        if (!this.isAboveBreakpoint() || !this.scroller) return;

        const scrollTop = this.scroller.scrollTop;
        const containerTop = this.container.offsetTop;
        const viewportHeight = this.scroller.clientHeight;
        const viewTop = scrollTop - containerTop;
        const viewBottom = viewTop + viewportHeight;
        const recycleBuffer = viewportHeight * 5;
        const growBuffer = viewportHeight * 3;

        this.recycleFromTop(viewTop, recycleBuffer);
        this.recycleFromBottom(viewBottom, recycleBuffer);

        const contentBottom = Math.max(...this.columnBottoms);

        if (viewBottom + growBuffer > contentBottom) {
            this.fillDownward(viewBottom + growBuffer * 2);
            this.updateContainerHeight();
            this.applyPositions();
        }

        const contentTop = Math.min(...this.columnTops);

        if (viewTop - growBuffer < contentTop) {
            this.fillUpward(viewTop - growBuffer * 2);
            this.applyPositions();
        }

        if (Math.min(...this.columnTops) < 0) {
            this.resetToMiddle();
        }
    }

    recycleFromTop(viewTop, buffer) {
        while (this.slots.length > this.templates.length) {
            const slot = this.slots[0];

            if (slot.verticalOffset + slot.height < viewTop - buffer) {
                this.releaseNodeToPool(slot.node, slot.templateIndex);
                this.slots.shift();
                this.headIndex++;
            } else {
                break;
            }
        }

        this.recalculateColumnTops();
    }

    recycleFromBottom(viewBottom, buffer) {
        while (this.slots.length > this.templates.length) {
            const slot = this.slots[this.slots.length - 1];

            if (slot.verticalOffset > viewBottom + buffer) {
                this.releaseNodeToPool(slot.node, slot.templateIndex);
                this.slots.pop();
                this.tailIndex--;
            } else {
                break;
            }
        }

        this.recalculateColumnBottoms();
        this.updateContainerHeight();
    }

    handleResize() {
        const isAboveBreakpoint = this.isAboveBreakpoint();
        const crossedBreakpoint = isAboveBreakpoint !== this.wasAboveBreakpoint;
        const newColumnCount = this.readColumnCount();
        const columnCountChanged = newColumnCount !== this.columns;

        if (!crossedBreakpoint && !columnCountChanged) return;

        this.wasAboveBreakpoint = isAboveBreakpoint;

        const hadScrollContainer = !!this.scroller;

        this.releaseAllSlots();
        this.active = false;

        if (!isAboveBreakpoint && hadScrollContainer) {
            this.removeScrollContainer();
        }

        this.originalElements.forEach((element) => (element.style.display = ""));
        this.container.style.position = "";
        this.container.style.display = "";
        this.container.style.height = "";
        this.activate();
    }
}

window.addEventListener("DOMContentLoaded", () => {
    const container = document.querySelector(".design__images");
    if (container) new InfiniteMasonry(container);
});
