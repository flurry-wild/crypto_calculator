import axios from 'axios';

const api = axios.create();

/*api.interceptors.request.use(config => {
    if (localStorage.getItem('access_token')) {
        config.headers.authorization = `Bearer ${localStorage.getItem('access_token')}`
    }

    return config;
}, error => {})*/

export default api;
