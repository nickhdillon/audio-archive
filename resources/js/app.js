import Cropper from "cropperjs";
import 'cropperjs/src/css/cropper.css';
import sort from '@alpinejs/sort';
import equalizer from './equalizer.js';

import '../../vendor/spatie/livewire-filepond/resources/dist/filepond.css';
import '../../vendor/spatie/livewire-filepond/resources/dist/filepond';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Cropper = Cropper;
 
Alpine.plugin(sort);

Alpine.data('equalizer', equalizer);

Livewire.start();
