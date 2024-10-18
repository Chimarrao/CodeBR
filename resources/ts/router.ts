import { createRouter, createWebHistory } from 'vue-router';
import Index from './components/index/App.vue';
import Artigo from './components/article/App.vue';
import Sobre from './components/about/App.vue';
import Contato from './components/contact/App.vue';
import PoliticaDePrivacidade from './components/privacity/App.vue';

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
        component: Contato,
    },
    {
        path: '/politica-de-privacidade',
        component: PoliticaDePrivacidade,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
