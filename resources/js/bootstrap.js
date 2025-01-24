import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
window.populateOffcanvas = function (data) {
    // Lógica para manejar los datos y popular el offcanvas

};
