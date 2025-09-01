import { createApp, reactive } from 'vue'
import App from './App.vue'
import './registerServiceWorker'
import router from './router'
import store from './store'
import './assets/tailwind.css'
import axios from '@/config/axiosConfig';
import '../node_modules/flowbite-vue/dist/index.css'
const app = createApp(App);
app.config.globalProperties.$axios = axios;
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
app.config.globalProperties.$user = reactive({
    role: null
});
app.config.globalProperties.$auxiliar = reactive({
    id: null,
    name: null,
    active: null,
    role: null,
    phone: null
});
app.config.globalProperties.$productAux = reactive({
    id: null,
    name: null,
    unit: null,
    description: null,
    dimension: null,
    active: null,
    image: null,
    id_family: null
});

app.use(store).use(router).mount('#app')
