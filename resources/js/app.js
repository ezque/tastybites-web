import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/inertia-vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import 'material-icons/iconfont/material-icons.css';

import '../css/app.css';
import axios from 'axios';

    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    const token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.warn('CSRF token not found: ensure <meta name="csrf-token" content="{{ csrf_token() }}"> is in your layout.');
}

createInertiaApp({
    resolve: name => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        const app = createApp({ render: () => h(App, props) });

        // Make axios available in all components via this.$axios
        app.config.globalProperties.$axios = axios;

        app.use(plugin).mount(el);
    },
});
