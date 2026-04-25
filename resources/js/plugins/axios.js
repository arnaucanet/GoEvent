import _ from 'lodash';
window._ = _;


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

window.axios.defaults.withCredentials = true

export const AUTH_TOKEN_KEY = 'auth_token'

export function setAuthToken(token) {
    if (token) {
        localStorage.setItem(AUTH_TOKEN_KEY, token)
    } else {
        localStorage.removeItem(AUTH_TOKEN_KEY)
    }
}

export function getAuthToken() {
    return localStorage.getItem(AUTH_TOKEN_KEY)
}

window.axios.interceptors.request.use(
    config => {
        const token = getAuthToken()
        if (token) {
            config.headers.Authorization = `Bearer ${token}`
        }
        return config
    },
    error => Promise.reject(error)
)

window.axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response?.status === 401 || error.response?.status === 403 || error.response?.status === 419) {
            if (error.response?.status === 401) {
                setAuthToken(null)
            }
            if (location.pathname !== '/login'){
                location.assign('/login')
            }
        }

        return Promise.reject(error)
    }
)

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     wsHost: import.meta.env.VITE_PUSHER_HOST ?? `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
