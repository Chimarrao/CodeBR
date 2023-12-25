import { alerts } from './alerts/alerts';

import './contato';
import './modoDark';
import './particulas';
import './comentarios';
import './menuHamburguer';
import './animacaoTextoBlog';

import 'bulma/css/bulma.min.css';
import '@fortawesome/fontawesome-free/css/all.css';
import './../css/styles.css';

const loadModules = async () => {
    const prismJsModule = await import(/* webpackChunkName: "prismJs" */ './prismJs');
};

document.addEventListener('DOMContentLoaded', async () => {
    await loadModules();
});

/**
 * Classes disponíveis na window
 */
(window as any).alerts = alerts;