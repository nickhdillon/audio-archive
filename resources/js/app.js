import Cropper from "cropperjs";
import 'cropperjs/src/css/cropper.css';
import equalizer from './equalizer.js';

import '../../vendor/spatie/livewire-filepond/resources/dist/filepond.css';
import '../../vendor/spatie/livewire-filepond/resources/dist/filepond';

import { Livewire, Alpine } from '../../vendor/livewire/livewire/dist/livewire.esm';

window.Cropper = Cropper;

Alpine.data('equalizer', equalizer);

Livewire.start();

import '@wotz/livewire-sortablejs';
