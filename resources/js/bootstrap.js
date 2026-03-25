import axios from 'axios';
import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';
import resize from '@alpinejs/resize'
import { gsap } from "gsap";
import { Flip } from "gsap/Flip";

import.meta.glob([
    '../images/**',
    '../favicons/**',
]);

window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

gsap.registerPlugin(Flip);
window.gsap = gsap;
window.Flip = Flip;

Alpine.plugin(resize);
Livewire.start();

