import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import axios from 'axios';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';

// Components
import App from './components/App.vue';
import Dashboard from './components/Dashboard.vue';
import Products from './components/Products.vue';
import Categories from './components/Categories.vue';
import Orders from './components/Orders.vue';
import Customers from './components/Customers.vue';
import Login from './components/Login.vue';

// Axios configuration
axios.defaults.baseURL = '/api';
axios.defaults.headers.common['Accept'] = 'application/json';
axios.defaults.headers.common['Content-Type'] = 'application/json';

// Add token to requests
const token = localStorage.getItem('token');
if (token) {
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}

// Router configuration
const routes = [
    { path: '/login', component: Login, name: 'login' },
    { path: '/', component: Dashboard, name: 'dashboard', meta: { requiresAuth: true } },
    { path: '/products', component: Products, name: 'products', meta: { requiresAuth: true } },
    { path: '/categories', component: Categories, name: 'categories', meta: { requiresAuth: true } },
    { path: '/orders', component: Orders, name: 'orders', meta: { requiresAuth: true } },
    { path: '/customers', component: Customers, name: 'customers', meta: { requiresAuth: true } },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Auth guard
router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token');
    
    if (to.matched.some(record => record.meta.requiresAuth)) {
        if (!token) {
            next('/login');
        } else {
            next();
        }
    } else {
        next();
    }
});

// Create app
const app = createApp(App);
app.use(router);
app.config.globalProperties.$http = axios;

app.mount('#app');
