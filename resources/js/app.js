import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy/dist/index.esm.js';
import { createVuetify } from 'vuetify';
import * as components from 'vuetify/components';
import * as directives from 'vuetify/directives';
import 'vuetify/styles';
import '@mdi/font/css/materialdesignicons.css';
import '../css/app.css';

const vuetify = createVuetify({
    components,
    directives,
    theme: {
        defaultTheme: 'pamplona',
        themes: {
            pamplona: {
                dark: false,
                colors: {
                    primary: '#C0252D',
                    'primary-darken-1': '#8B0000',
                    secondary: '#F9C300',
                    'secondary-darken-1': '#C49A00',
                    accent: '#1A237E',
                    error: '#C0252D',
                    info: '#1565C0',
                    success: '#2E7D32',
                    warning: '#F57F17',
                    background: '#FAFAFA',
                    surface: '#FFFFFF',
                },
            },
        },
    },
    defaults: {
        VBtn: {
            rounded: 'lg',
            elevation: 0,
        },
        VCard: {
            rounded: 'xl',
            elevation: 2,
        },
        VChip: {
            rounded: 'lg',
        },
    },
});

createInertiaApp({
    title: (title) => `${title} — Directorio Pamplona`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob('./Pages/**/*.vue'),
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(vuetify)
            .mount(el);
    },
    progress: {
        color: '#F9A825',
    },
});
