import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/index/App.vue';
import Artigo from './components/article/App.vue';

const routes = [
    {
        path: '/',
        component: Home,
    },
    {
        path: '/page/:pageNumber',
        component: Home,
    },
    {
        path: '/artigo/:slug',
        component: Artigo,
        props: true
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
