<template>
    <Menu />
    <Cabecalho />
    <SkeletonLoader v-if="loading" />

    <section class="hero is-medium is-dark is-bold">
        <div class="hero-body">
            <div class="container text-center">
                <div class="columns is-centered">
                    <div class="column is-two-thirds">
                        <h1 class="title artigo">
                            {{ artigo.artigo }}
                        </h1>
                        <h2 class="subtitle">
                            {{ artigo.descricao }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section artigo">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-one"></div>
                <div class="column is-two-thirds">
                    <!-- Aqui percorremos o conteúdo do artigo para detectar blocos de código -->
                    <template v-for="(item, index) in processarTexto(artigo.texto)" :key="index">
                        <!-- <CodeHighlight  :language="item.language" :code="item.content" /> -->

                        <code-highlight
                            v-if="item.isCode"
                            :language="item.language"
                        >
                        {{ item.content }}
                        </code-highlight>
                        <div v-else v-html="item.content"></div>
                    </template>
                </div>
                <div class="column is-one"></div>
            </div>
        </div>
    </section>


    <div class="section comentarios pt-0">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-two-thirds">
                    <h2 class="title is-3">Comentários</h2>

                    <FormComentario @comentarioEnviado="adicionarComentario" />

                    <div class="bloco-comentarios mt-2">
                        <Comentario v-for="comentario in comentarios" :key="comentario.id" :comentario="comentario" />
                    </div>
                </div>
            </div>
        </div>
    </div>

    <Rodape />
</template>

<script>
import Menu from './../Menu.vue';
import Rodape from './../Rodape.vue';
import Cabecalho from './../Cabecalho.vue';
import FormComentario from "./FormComentario.vue";
import Comentario from "./Comentario.vue";
import SkeletonLoader from "./SkeletonLoader.vue";

import CodeHighlight from "vue-code-highlight/src/CodeHighlight.vue";
import "vue-code-highlight/themes/duotone-sea.css";
import "vue-code-highlight/themes/window.css";

export default {
    components: {
        Menu,
        Rodape,
        Cabecalho,
        FormComentario,
        Comentario,
        SkeletonLoader,
        CodeHighlight
    },
    data() {
        return {
            artigo: [],
            comentarios: [],
            loading: false,
        };
    },
    methods: {
        async fetchArtigo() {
            this.loading = true;
            const slug = window.location.pathname.split("/artigo/")[1].split("/")[0];

            try {
                const resposta = await fetch(`/api/artigo?slug=${slug}`);
                const resultado = await resposta.json();

                if (resultado.success) {
                    this.artigo = resultado.data.artigo;
                    this.comentarios = resultado.data.comentarios;

                    this.$nextTick(() => {
                        this.executarScripts();
                    });
                } else {
                    console.error("Erro ao carregar os artigos:", resultado.message);
                }
            } catch (error) {
                console.error("Erro ao buscar os artigos:", error);
            }

            this.loading = false;
        },

        adicionarComentario(novoComentario) {
            this.fetchArtigo();
        },

        processarTexto(texto) {
            const resultado = [];
            const regex = /<pre.*?><code class="language-(\w+)">([\s\S]*?)<\/code><\/pre>/g;
            let match, lastIndex = 0;

            while ((match = regex.exec(texto)) !== null) {
                if (match.index > lastIndex) {
                    resultado.push({
                        isCode: false,
                        content: texto.slice(lastIndex, match.index),
                    });
                }

                resultado.push({
                    isCode: true,
                    language: match[1],
                    content: this.decodeHTMLEntities(match[2]),
                });

                lastIndex = regex.lastIndex;
            }

            if (texto && lastIndex < parseInt(texto.length)) {
                resultado.push({
                    isCode: false,
                    content: texto.slice(lastIndex),
                });
            }

            return resultado;
        },

        decodeHTMLEntities(texto) {
            const textarea = document.createElement("textarea");
            textarea.innerHTML = texto;
            return textarea.value;
        },

        executarScripts() {
            const scripts = document.querySelectorAll(".container script");
            scripts.forEach((script) => {
                const novoScript = document.createElement("script");
                if (script.src) {
                    novoScript.src = script.src;
                } else {
                    novoScript.innerHTML = script.innerHTML;
                }
                document.body.appendChild(novoScript);
                document.body.removeChild(novoScript);
            });
        }
    },
    mounted() {
        this.fetchArtigo();

        this.$nextTick(() => {
            this.executarScripts();
        });
    }
};
</script>
