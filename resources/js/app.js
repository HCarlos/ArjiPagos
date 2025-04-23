import '../css/app.css';
import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
// import PrimeVue from 'primevue/config';
// import DataTable from 'primevue/datatable';
// import Column    from 'primevue/column';
// import Paginator from 'primevue/paginator';    // si quieres un paginador custom
// import 'primevue/resources/themes/saga-blue/theme.css';     // tema
// import 'primevue/resources/primevue.min.css';               // estilos core
// import 'primeicons/primeicons.css';                         // iconos
// import 'primeflex/primeflex.css';                           // utilidades flex (opcional)



// import 'flowbite';

// import $ from 'jquery';
// window.$ = $;
// window.jQuery = $;

// import 'select2/dist/js/select2.min.js';
// import 'select2/dist/css/select2.min.css';

// import VuelidatePlugin from '@vuelidate/core';

// import { useFavicon } from '@vueuse/core'

import LaravelPermissionToVueJS from 'laravel-permission-to-vuejs'

const appName = import.meta.env.APP_NAME || 'Laravel';

// axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(LaravelPermissionToVueJS)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});

// .component('DataTable', DataTable)
//     .component('Column', Column)
//     .component('Paginator', Paginator)
