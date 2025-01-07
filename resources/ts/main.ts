// @ts-ignore
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

const app = createApp(App); 

app.use(router);
app.mount('#app');


import { alerts } from './alerts/alerts';

import './modoDark';
import './menuHamburguer';

import '@fortawesome/fontawesome-free/css/all.css';
import './../css/styles.css';

/**
 * Classes disponíveis na window
 */
(window as any).alerts = alerts;