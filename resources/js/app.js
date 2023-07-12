import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import Menu from './components/Menu.vue'

const app = createApp(App)
app.component('Menubar',Menu);
app.use(router);
app.mount('#app-vue');
