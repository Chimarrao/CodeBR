<template>
    <Menu />

    <section class="hero is-medium is-dark is-bold">
        <div class="hero-body">
            <div class="container has-text-centered">
                <h1 class="title">Code BR</h1>
                <h2 class="subtitle" id="subtitulo">Bem-vindo ao meu blog de programação!</h2>
            </div>
        </div>
    </section>

    <div class="container">
        <p class="is-size-5 mt-6">
            Olá! Meu nome é Lucas Reolon e sou um entusiasta de programação de {{ idade }} anos. Estou animado em compartilhar
            minha experiência e conhecimento em codificação com você através do meu blog no
            <strong>codebr.net</strong>.
        </p>

        <p class="is-size-5 mt-5">
            Aqui, você encontrará artigos sobre várias linguagens de programação, frameworks e tecnologias, juntamente
            com tutoriais passo a passo para ajudar você a se tornar um programador mais habilidoso e confiante.
        </p>

        <p class="is-size-5 mt-5">
            Como um apaixonado por programação, tenho trabalhado em vários projetos e desenvolvendo minhas habilidades
            de programação ao longo dos anos. Através deste blog, quero compartilhar com vocês algumas das coisas que
            aprendi ao longo do caminho e fornecer informações valiosas para ajudá-lo a alcançar seus objetivos de
            programação.
        </p>

        <p class="is-size-5 mt-5">
            Espero que meus artigos possam ajudá-lo a expandir seus conhecimentos e habilidades de programação e que
            você se divirta aprendendo comigo. Além disso, estou sempre aberto a sugestões e comentários para melhorar o
            meu blog e fornecer o melhor conteúdo possível para a comunidade de programação.
        </p>

        <p class="is-size-5 mt-5">
            Obrigado por visitar o meu blog e espero vê-lo novamente em breve! 👍💻
        </p>
    </div>

    <Rodape />
</template>

<script>
import Menu from './../Menu.vue';
import Rodape from './../Rodape.vue';

export default {
    components: {
        Menu,
        Rodape,
    },
    data() {
        return {
            idade: new Date().getFullYear() - 1998 - (new Date().getMonth() < 6 || (new Date().getMonth() === 6 && new Date().getDate() < 8) ? 1 : 0)
        };
    },
    computed: {

    },
};
</script>
