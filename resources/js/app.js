import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import { createStore } from 'vuex';
import App from './components/App.vue';
import Dashboard from './components/Dashboard.vue';
import New from './components/New.vue';
import Login from './components/Login.vue';
import ElementPlus from 'element-plus'
import 'element-plus/dist/index.css'
import axios from 'axios';

const routes = [
    { path: '/', component: Dashboard },
    { path: '/new', component: New },
    { path: '/login', component: Login },
    { path: '/edit/:id', component: New } // Use New component for Edit path
];

const router = createRouter({
    history: createWebHistory(),
    routes
});

async function revalidateToken() {
    const token = store.state.token || localStorage.getItem('token');
    if (token) {
        try {
            await axios.post('/api/revalidate-token', { token });
        } catch (error) {
            store.commit('setToken', null);
            localStorage.removeItem('token');
            router.push('/login');
        }
    }
}

router.beforeEach(async (to, from, next) => {
    const publicPages = ['/login'];
    const authRequired = !publicPages.includes(to.path);
    const loggedIn = store.state.token || localStorage.getItem('token');

    if (authRequired && loggedIn) {
        await revalidateToken();
    }

    if (authRequired && !loggedIn) {
        return next('/login');
    }

    next();
});

const store = createStore({
    state: {
        user: null,
        token: null,
        travelRequests: []
    },
    mutations: {
        setUser(state, user) {
            state.user = user;
        },
        setToken(state, token) {
            state.token = token;
        },
        setTravelRequests(state, travelRequests) {
            state.travelRequests = travelRequests;
        }
    },
    actions: {
        // ...actions for login, logout, fetch travel requests, etc.
    }
});

const app = createApp(App);

app.use(router);
app.use(store);
app.use(ElementPlus);
app.mount('#app');