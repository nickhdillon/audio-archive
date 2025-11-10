import Cropper from "cropperjs";
import 'cropperjs/src/css/cropper.css';
import sort from '@alpinejs/sort'

import '../../vendor/spatie/livewire-filepond/resources/dist/filepond.css';
import '../../vendor/spatie/livewire-filepond/resources/dist/filepond';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

// import player from './player.js';

// document.addEventListener('alpine:init', () => {
//     Alpine.store('player', player);
// });

window.Cropper = Cropper;
 
Alpine.plugin(sort);

Livewire.start();
