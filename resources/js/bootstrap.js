/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

// Axios
import axios from 'axios';
window.axios = axios;
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Lodash
import _ from 'lodash';
window._ = _;

// Font Awesome
import '@fortawesome/fontawesome-free/js/all.js';

/* SweetAlert2 */
import Swal from 'sweetalert2';
window.Swal = Swal;
window.Swal.confirm = (mode = 'normal', message = '') => {

    if(mode === 'normal') {

        return Swal.fire({
            title: '確認',
            html: (message)
                ? message
                : '送信します。よろしいですか？',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#1997ab',
            confirmButtonText: '送信する',
            cancelButtonText: 'キャンセル',
            reverseButtons: true,
        });

    } else if(mode === 'cancel') {

        return Swal.fire({
            title: '内容の破棄',
            html: (message)
                ? message
                : '内容を破棄します。本当によろしいですか？',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1997ab',
            cancelButtonText: '破棄しない',
            confirmButtonText: '破棄する',
            reverseButtons: true,
        });

    } else if(mode === 'delete') {

        return Swal.fire({
            title: '削除',
            html: (message)
                ? message
                : '削除します。本当によろしいですか？<br>※この操作は<b>取り消せません。</b>',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#1997ab',
            cancelButtonText: 'キャンセル',
            confirmButtonText: '削除する',
            reverseButtons: true,
        });

    }

    throw new Error('Invalid mode');

};
window.Swal.success = (message = '保存が完了しました') => {

    return Swal.fire({
        title: '完了',
        text: message,
        icon: 'success',
    });

};
window.Swal.error = (message = '保存できませんでした') => {

    return Swal.fire({
        title: 'エラー',
        text: message,
        icon: 'error',
    });

};
window.Swal.input = (message, label, defaultValue = '') => {

    return Swal.fire({
        title: message,
        input: "text",
        inputLabel: label,
        defaultValue: defaultValue,
        showCancelButton: true,
        confirmButtonText: '送信',
        cancelButtonText: 'キャンセル',
    });

};

/* Sketchfab */
import Sketchfab from '@sketchfab/viewer-api';
window.Sketchfab = Sketchfab;

/* Autosize */
import autosize from 'autosize';
window.autosize = autosize;

// TomSelect
import TomSelect from 'tom-select';
import 'tom-select/dist/css/tom-select.css';
window.TomSelect = TomSelect;

// CSS
import '../css/custom.css';

/* for Test */
window.dd = console.log;

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
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST ? import.meta.env.VITE_PUSHER_HOST : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
