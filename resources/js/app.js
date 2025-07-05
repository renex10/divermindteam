import './bootstrap';
import '../css/app.css';

import { createApp, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

import { plugin as FormKit, defaultConfig } from '@formkit/vue';
import '@formkit/themes/genesis';

// Importar vue-toast-notification
import ToastPlugin from 'vue-toast-notification';
import 'vue-toast-notification/dist/theme-bootstrap.css'; // Podés cambiar el tema si querés

const appName = import.meta.env.VITE_APP_NAME || 'Laravel';

createInertiaApp({
  title: (title) => `${title} - ${appName}`,
  resolve: (name) =>
    resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
  setup({ el, App, props, plugin }) {
    // 1️⃣ Crear la instancia de la app
    const vueApp = createApp({ render: () => h(App, props) });

    // 2️⃣ Registrar los plugins
    vueApp
      .use(plugin)
      .use(ZiggyVue)
      .use(FormKit, defaultConfig())
      .use(ToastPlugin, {
        position: 'top-right',
        duration: 5000,
      });

    // 3️⃣ Mostrar el toast si Laravel lo envía por props
    const toastData = props.initialPage.props.toast;
    if (toastData && toastData.message) {
      const toast = vueApp.config.globalProperties.$toast;

      switch (toastData.type) {
        case 'success':
          toast.success(toastData.message);
          break;
        case 'error':
          toast.error(toastData.message);
          break;
        case 'info':
          toast.info(toastData.message);
          break;
        case 'warning':
          toast.warning(toastData.message);
          break;
        default:
          toast.open(toastData.message);
      }
    }

    // 4️⃣ Montar la app
    vueApp.mount(el);
  },
  progress: {
    color: '#4B5563',
  },
});