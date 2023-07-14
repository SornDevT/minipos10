import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import Menu from './components/Menu.vue'
import { createPinia } from 'pinia';
const pinia = createPinia()

const app = createApp(App)
app.component('Menubar',Menu);
app.use(router);
app.use(pinia);
app.mount('#app-vue');
