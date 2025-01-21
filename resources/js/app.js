/*import './bootstrap';
import * as bootstrap from 'bootstrap';*/

import './bootstrap';
import * as bootstrap from 'bootstrap';
import { createPopper } from '@popperjs/core'; // Importa Popper.js
import * as XLSX from 'xlsx';

window.bootstrap = bootstrap; // Esto hace que Bootstrap sea accesible desde window
window.Popper = createPopper;  // Hace que Popper esté disponible globalmente
window.XLSX = XLSX; // Hace que XLSX esté disponible globalmente

import '@fortawesome/fontawesome-free/scss/fontawesome.scss';
import '@fortawesome/fontawesome-free/scss/brands.scss';
import '@fortawesome/fontawesome-free/scss/regular.scss';
import '@fortawesome/fontawesome-free/scss/solid.scss';
import '@fortawesome/fontawesome-free/scss/v4-shims.scss';