import { createRouter, createWebHistory } from 'vue-router';
import Index from './components/index/App.vue';
import Artigo from './components/article/App.vue';
import Sobre from './components/about/App.vue';

const routes = [
    {
        path: '/',
        component: Index,
    },
    {
        path: '/page/:pageNumber',
        component: Index,
    },
    {
        path: '/artigo/:slug',
        component: Artigo,
        props: true
    },
    {
        path: '/sobre',
        component: Sobre,
    },
    {
        path: '/contato',
        component: Index,
    },
    {
        path: '/politica-de-privacidade',
        component: Index,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
