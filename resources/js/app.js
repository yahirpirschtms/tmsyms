import './bootstrap';
import * as bootstrap from 'bootstrap';
import { createPopper } from '@popperjs/core'; // Importa Popper.js
import * as XLSX from 'xlsx';

window.bootstrap=bootstrap;
window.Popper = createPopper;
window.XLSX = XLSX;
import '@fortawesome/fontawesome-free/scss/fontawesome.scss';
import '@fortawesome/fontawesome-free/scss/brands.scss';
import '@fortawesome/fontawesome-free/scss/regular.scss';
import '@fortawesome/fontawesome-free/scss/solid.scss';
import '@fortawesome/fontawesome-free/scss/v4-shims.scss';


