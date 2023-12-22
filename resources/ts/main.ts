import './modoDark';
import './particulas';
import './menuHamburguer';
import './animacaoTextoBlog';

import 'bulma/css/bulma.min.css';
import '@fortawesome/fontawesome-free/css/all.css';
import './../css/styles.css';

const loadModules = async () => {
    const prismJsModule = await import(/* webpackChunkName: "prismJs" */ './prismJs');
    const contatoModule = await import(/* webpackChunkName: "contato" */ './contato');
    const comentariosModule = await import(/* webpackChunkName: "comentarios" */ './comentarios');
};

document.addEventListener('DOMContentLoaded', async () => {
    await loadModules();
});