import { createRouter, createWebHistory } from 'vue-router';

const routes = [
    {
        path: '/',
        component: () => import(/* webpackChunkName: "index" */ './components/index/App.vue'),
    },
    {
        path: '/page/:pageNumber',
        component: () => import(/* webpackChunkName: "index" */ './components/index/App.vue'),
    },
    {
        path: '/artigo/:slug',
        component: () => import(/* webpackChunkName: "artigo" */ './components/article/App.vue'),
        props: true
    },
    {
        path: '/sobre',
        component: () => import(/* webpackChunkName: "sobre" */ './components/about/App.vue'),
    },
    {
        path: '/contato',
        component: () => import(/* webpackChunkName: "contato" */ './components/contact/App.vue'),
    },
    {
        path: '/politica-de-privacidade',
        component: () => import(/* webpackChunkName: "privacidade" */ './components/privacity/App.vue'),
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

export default router;
