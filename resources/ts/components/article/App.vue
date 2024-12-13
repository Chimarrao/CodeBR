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
                    <template v-for="(item, index) in processarTexto(artigo.texto)" :key="index">
                        <prism v-if="item.isCode" :language="item.language">{{ item.content }}</prism>
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

import Prism from 'vue-prism-component'
import 'prismjs/themes/prism-okaidia.css'
import 'prismjs/components/prism-bash'
import 'prismjs/components/prism-batch'
import 'prismjs/components/prism-python'
import 'prismjs/components/prism-css'
import 'prismjs/components/prism-javascript';
import 'prismjs/components/prism-c';
import 'prismjs/components/prism-cpp';
import 'prismjs/components/prism-typescript';
import 'prismjs/components/prism-sql';
import 'prismjs/components/prism-rust';
import 'prismjs/components/prism-java';
import 'prismjs/components/prism-json';
import 'prismjs/components/prism-jsx';
import 'prismjs/components/prism-tsx';
import 'prismjs/components/prism-markup-templating'
import 'prismjs/components/prism-php';

/* Linguagens não usadas removidas para reduzir o peso do bundle */
// import 'prismjs/components/prism-markdown';

import 'prismjs/plugins/toolbar/prism-toolbar.js';
import 'prismjs/plugins/toolbar/prism-toolbar.css';

import 'prismjs/plugins/inline-color/prism-inline-color.css'
import 'prismjs/plugins/inline-color/prism-inline-color.js'

import 'prismjs/plugins/normalize-whitespace/prism-normalize-whitespace.js'

import 'prismjs/plugins/treeview/prism-treeview.css'
import 'prismjs/plugins/treeview/prism-treeview.js'

import 'prismjs/plugins/diff-highlight/prism-diff-highlight.css'
import 'prismjs/plugins/diff-highlight/prism-diff-highlight.js'

import 'prismjs/plugins/match-braces/prism-match-braces.css'
import 'prismjs/plugins/match-braces/prism-match-braces.js'

import 'prismjs/plugins/copy-to-clipboard/prism-copy-to-clipboard.js'

export default {
    components: {
        Menu,
        Rodape,
        Cabecalho,
        FormComentario,
        Comentario,
        SkeletonLoader,
        Prism
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

                let codigMatch = this.decodeHTMLEntities(match[2]);

                const lines = codigMatch.split('\n');

                if (lines.length > 0 && lines[0].trim() === '') {
                    lines.shift();
                }

                codigMatch = lines.join('\n');

                resultado.push({
                    isCode: true,
                    language: match[1] == 'none' ? 'plaintext' : match[1],
                    content: codigMatch,
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

<style scoped>
/**
 * atom-dark theme for `prism.js`
 * Based on Atom's `atom-dark` theme: https://github.com/atom/atom-dark-syntax
 * @author Joe Gibson (@gibsjose)
 */

code[class*="language-"],
pre[class*="language-"] {
    color: #c5c8c6;
    text-shadow: 0 1px rgba(0, 0, 0, 0.3);
    font-family: Inconsolata, Monaco, Consolas, 'Courier New', Courier, monospace;
    direction: ltr;
    text-align: left;
    white-space: pre;
    word-spacing: normal;
    word-break: normal;
    line-height: 1.5;

    -moz-tab-size: 4;
    -o-tab-size: 4;
    tab-size: 4;

    -webkit-hyphens: none;
    -moz-hyphens: none;
    -ms-hyphens: none;
    hyphens: none;
}

/* Code blocks */
pre[class*="language-"] {
    padding: 1em;
    margin: .5em 0;
    overflow: auto;
    border-radius: 0.3em;
}

:not(pre)>code[class*="language-"],
pre[class*="language-"] {
    background: #1d1f21;
}

/* Inline code */
:not(pre)>code[class*="language-"] {
    padding: .1em;
    border-radius: .3em;
}

.token.comment,
.token.prolog,
.token.doctype,
.token.cdata {
    color: #7C7C7C;
}

.token.punctuation {
    color: #c5c8c6;
}

.namespace {
    opacity: .7;
}

.token.property,
.token.keyword,
.token.tag {
    color: #96CBFE;
}

.token.class-name {
    color: #FFFFB6;
    text-decoration: underline;
}

.token.boolean,
.token.constant {
    color: #99CC99;
}

.token.symbol,
.token.deleted {
    color: #f92672;
}

.token.number {
    color: #FF73FD;
}

.token.selector,
.token.attr-name,
.token.string,
.token.char,
.token.builtin,
.token.inserted {
    color: #A8FF60;
}

.token.variable {
    color: #C6C5FE;
}

.token.operator {
    color: #EDEDED;
}

.token.entity {
    color: #FFFFB6;
    cursor: help;
}

.token.url {
    color: #96CBFE;
}

.language-css .token.string,
.style .token.string {
    color: #87C38A;
}

.token.atrule,
.token.attr-value {
    color: #F9EE98;
}

.token.function {
    color: #DAD085;
}

.token.regex {
    color: #E9C062;
}

.token.important {
    color: #fd971f;
}

.token.important,
.token.bold {
    font-weight: bold;
}

.token.italic {
    font-style: italic;
}
</style>