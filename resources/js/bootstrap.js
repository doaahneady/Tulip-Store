/**
 * We'll load this axios instance by default for use with AJAX requests.
 * You may modify this behavior if needed.
 */
import axios from 'axios';

window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
