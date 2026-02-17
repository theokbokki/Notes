export default class BackdropLeaves {
    constructor(el) {
        this.el = el;

        this.leafCount = 90;
        this.seed = 802;
        this.width = 1440;
        this.height = 900;
        this.maxY = this.height * 0.4;
        this.topSpread = 0.4 * this.width;
        this.bottomSpread = -0.1 * this.width;

        this.init();
    }

    rand() {
        this.seed = (this.seed * 1664525 + 1013904223) % 4294967296;

        return this.seed / 4294967296;
    }

    init() {
        for (let i = 0; i < this.leafCount; i++) {
            this.createLeaf();
        }
    }

    createLeaf() {
        const leaf = document.createElement("div");
        leaf.className = "backdrop__leaf";

        const t = this.rand();
        const yNorm = Math.pow(t, 2.5);
        const yPx = yNorm * this.maxY;

        const spread =
            this.topSpread + yNorm * (this.bottomSpread - this.topSpread);

        const xPx = this.rand() * spread;

        const rotation = 20 + this.rand() * 40;
        const scale = 0.65 + this.rand() * 0.8;
        const opacity = 0.45 + this.rand() * 0.5;

        leaf.style.right = `${xPx}px`;
        leaf.style.top = `${yPx}px`;
        leaf.style.opacity = opacity;

        leaf.style.setProperty("--r", `${rotation}deg`);
        leaf.style.transform = `scale(${scale})`;

        const swayDuration = 2.5 + this.rand();
        const swayDelay = -this.rand() * 10;
        const swayDistance = 12 + this.rand() * 20;

        leaf.style.setProperty("--sway-duration", `${swayDuration}s`);
        leaf.style.setProperty("--sway-delay", `${swayDelay}s`);
        leaf.style.setProperty("--sway-distance", `${swayDistance}px`);

        this.el.appendChild(leaf);
    }
}
