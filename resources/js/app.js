import "./bootstrap";
import BackdropLeaves from "./parts/BackdropLeaves";

window.addEventListener('DOMContentLoaded', () => {
    const leavesEl = document.querySelector(".backdrop__leaves");

    if (leavesEl) {
        new BackdropLeaves(leavesEl);
    }
});
