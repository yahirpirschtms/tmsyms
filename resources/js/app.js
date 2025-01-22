import './bootstrap';
import * as bootstrap from 'bootstrap';
import { createPopper } from '@popperjs/core'; // Importa Popper.js


window.bootstrap=bootstrap;
window.Popper = createPopper;

import '@fortawesome/fontawesome-free/scss/fontawesome.scss';
import '@fortawesome/fontawesome-free/scss/brands.scss';
import '@fortawesome/fontawesome-free/scss/regular.scss';
import '@fortawesome/fontawesome-free/scss/solid.scss';
import '@fortawesome/fontawesome-free/scss/v4-shims.scss';

function populateOffcanvas(data) {
    // Lógica para manejar los datos y popular el offcanvas
    console.log(data);
}
