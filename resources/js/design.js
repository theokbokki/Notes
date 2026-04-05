import "./bootstrap";

class Design {
    constructor() {
        this.el = document.querySelector(".design");
        this.container = this.el.querySelector(".design__images");
        this.gap = 8;
        this.positions = [];
        this.totalItems = 0;
        this.columnTops = [];
        this.pool = [];
        this.attached = new Map();

        this.waitForImages().then(() => this.init());
    }

    waitForImages() {
        const images = this.el.querySelectorAll(".design__image");

        return Promise.all(Array.from(images).map((img) => {
            if (img.complete) {
                return Promise.resolve();
            }

            return new Promise((resolve) => {
                img.addEventListener("load", resolve, { once: true });
                img.addEventListener("error", resolve, { once: true });
            });
        }));
    }

    init() {
        this.measureTemplates();
        this.prepareContainer();
        this.createSentinel();
        this.fillToHeight(window.innerHeight * 3);
        this.render();
        this.listen();
    }

    measureTemplates() {
        const images = Array.from(this.el.querySelectorAll(".design__image"));

        this.templates = images.map((img) => ({
            src: img.src,
            ratio: img.naturalHeight / img.naturalWidth || 1,
        }));

        images.forEach((img) => img.remove());
    }

    prepareContainer() {
        this.columns = this.getColumnCount();
        this.colWidth = this.getColumnWidth();
        this.columnTops = new Array(this.columns).fill(0);
        this.container.style.position = "relative";
        this.container.style.display = "block";
    }

    getColumnCount() {
        return window
            .getComputedStyle(this.container)
            .getPropertyValue("grid-template-columns")
            .split(" ").length;
    }

    getColumnWidth() {
        const width = this.container.offsetWidth;

        return (width - this.gap * (this.columns - 1)) / this.columns;
    }

    createSentinel() {
        this.sentinel = document.createElement("div");

        this.sentinel.style.position = "absolute";
        this.sentinel.style.width = "1px";
        this.sentinel.style.height = "1px";

        this.container.appendChild(this.sentinel);
    }

    fillToHeight(targetHeight) {
        while (Math.max(...this.columnTops) < targetHeight) {
            this.addBatch();
        }

        this.updateHeight();
    }

    addBatch() {
        this.templates.forEach((template, i) => {
            const col = this.totalItems % this.columns;
            const height = template.ratio * this.colWidth;
            const x = col * (this.colWidth + this.gap);
            const y = this.columnTops[col];

            this.positions.push({
                x,
                y,
                w: this.colWidth,
                h: height,
                templateIdx: i,
            });

            this.columnTops[col] = y + height + this.gap;

            this.totalItems++;
        });
    }

    updateHeight() {
        const height = Math.max(...this.columnTops);

        this.sentinel.style.transform = `translateY(${height}px)`;

        this.container.style.height = `${height}px`;
    }

    listen() {
        window.addEventListener("scroll", () => this.render());
        window.addEventListener("resize", () => this.rebuild());
    }

    render() {
        const viewTop = this.getViewTop();
        const viewBottom = viewTop + window.innerHeight;
        const buffer = window.innerHeight;

        this.ensureContentFor(viewBottom + buffer * 2);

        const visible = this.getVisibleIndices(
            viewTop - buffer,
            viewBottom + buffer,
        );

        this.detachHidden(visible);
        this.attachVisible(visible);
    }

    getViewTop() {
        return (this.container.offsetTop - this.container.getBoundingClientRect().top);
    }

    ensureContentFor(targetBottom) {
        if (Math.max(...this.columnTops) < targetBottom) {
            this.fillToHeight(targetBottom);
        }
    }

    getVisibleIndices(top, bottom) {
        const visible = new Set();

        for (let i = 0; i < this.totalItems; i++) {
            const pos = this.positions[i];

            if (pos.y + pos.h >= top && pos.y <= bottom) {
                visible.add(i);
            }
        }

        return visible;
    }

    detachHidden(visible) {
        for (const [idx, node] of this.attached) {
            if (!visible.has(idx)) {
                node.style.display = "none";

                this.pool.push(node);
                this.attached.delete(idx);
            }
        }
    }

    attachVisible(visible) {
        for (const idx of visible) {
            if (this.attached.has(idx)) continue;

            const pos = this.positions[idx];
            const node = this.getNode(pos);

            node.style.display = "";
            node.style.width = `${pos.w}px`;
            node.style.transform = `translate(${pos.x}px, ${pos.y}px)`;

            this.attached.set(idx, node);
        }
    }

    getNode(pos) {
        let node = this.pool.pop();

        if (!node) {
            node = document.createElement("img");

            node.className = "design__image";
            node.style.position = "absolute";

            this.container.appendChild(node);
        }

        node.src = this.templates[pos.templateIdx].src;

        return node;
    }

    rebuild() {
        this.columns = this.getColumnCount();
        this.colWidth = this.getColumnWidth();
        this.positions = [];
        this.totalItems = 0;
        this.columnTops = new Array(this.columns).fill(0);

        this.fillToHeight(window.innerHeight * 3);

        for (const [, node] of this.attached) {
            node.style.display = "none";

            this.pool.push(node);
        }

        this.attached.clear();

        this.render();
    }
}

window.addEventListener("DOMContentLoaded", () => {
    new Design();
});
