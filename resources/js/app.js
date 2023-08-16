import './bootstrap';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import Menu from './components/Menu.vue'
import { createPinia } from 'pinia';
const pinia = createPinia()

import VueSweetalert2 from 'vue-sweetalert2';
import 'sweetalert2/dist/sweetalert2.min.css';

import Pagination from './components/Pagination.vue';
import Cleave from 'vue-cleave-component';

import GrapBoard from './components/GrapBoard.vue'

const app = createApp(App)
app.component('Menubar',Menu);
app.component('pagination',Pagination);
app.component('GrapBoard',GrapBoard);
app.use(VueSweetalert2);
app.use(router);
app.use(pinia);
app.use(Cleave);
app.mount('#app-vue');
