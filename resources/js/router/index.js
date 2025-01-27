import { createRouter, createWebHistory } from 'vue-router';

// Importar componentes das páginas
import Home from '../components/Home.vue';
import Dashboard from '../components/Dashboard.vue';
import Login from '../components/Login.vue';
import New from '../components/New.vue';
import Register from '../components/Register.vue';

const routes = [
    { path: '/', component: Home, name: 'home' },
    { path: '/dashboard', component: Dashboard, name: 'dashboard', meta: { requiresAuth: true } },
    { path: '/login', component: Login, name: 'login', meta: { requiresAuth: false } },
    { path: '/new', component: New, name: 'new' },
    { path: '/edit/:id', component: New, name: 'edit' },
    { path: '/register', component: Register, name: 'register', meta: { requiresAuth: false } }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// Proteger rotas com autenticação
router.beforeEach((to, from, next) => {
    const isAuthenticated = !!localStorage.getItem('token');
    if (to.meta.requiresAuth && !isAuthenticated) {
        next('/login');
    } else {
        next();
    }
});

export default router;