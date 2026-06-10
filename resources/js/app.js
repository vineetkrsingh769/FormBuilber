import Alpine from 'alpinejs';
import { formBuilder } from './form-builder/index.js';

window.Alpine = Alpine;
Alpine.data('formBuilder', formBuilder);
Alpine.start();
