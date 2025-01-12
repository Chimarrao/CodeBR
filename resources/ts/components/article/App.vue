<template>
    <Menu />
    <Cabecalho />

    <section class="hero is-medium is-dark is-bold" style="min-height: 300px;">
        <div class="hero-body">
            <div class="container text-center">
                <div class="columns is-centered">
                    <div class="column is-two-thirds">
                        <!-- Skeleton para o título -->
                        <h1 v-if="loading" class="skeleton-title skeleton-artigo-title"></h1>
                        <h1 v-else class="title artigo">
                            {{ artigo.artigo }}
                        </h1>

                        <!-- Skeleton para o subtítulo -->
                        <h2 v-if="loading" class="skeleton-subtitle skeleton-artigo-subtitle"></h2>
                        <h2 v-else class="subtitle">
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
                <div class="column is-two-thirds" v-if="loading">
                    <SkeletonLoader />
                </div>
                <div class="column is-two-thirds" v-else>
                    <template v-for="(item, index) in processarTexto(artigo.texto_dark)" :key="index">
                        <div v-if="item.isCode" v-html="item.content"></div>
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
import FormComentario from './FormComentario.vue';
import Comentario from './Comentario.vue';
import SkeletonLoader from './SkeletonLoader.vue';

import 'highlight.js/styles/monokai.css';

export default {
    components: {
        Menu,
        Rodape,
        Cabecalho,
        FormComentario,
        Comentario,
        SkeletonLoader,
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
                    console.error('Erro ao carregar os artigos:', resultado.message);
                }
            } catch (error) {
                console.error('Erro ao buscar os artigos:', error);
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
                    content: match[0],
                });

                lastIndex = regex.lastIndex;
            }

            if (texto && lastIndex < texto.length) {
                resultado.push({
                    isCode: false,
                    content: texto.slice(lastIndex),
                });
            }

            return resultado;
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
        },

        copyCode(button) {
            const codeBlock = button.nextElementSibling.querySelector('code');
            navigator.clipboard.writeText(codeBlock.innerText).then(() => {
                button.innerHTML = '<i class="fa-solid fa-circle-check"></i>';
                setTimeout(() => {
                    button.innerHTML = '<i class="fa-solid fa-copy"></i>';
                }, 2000);
            }).catch(err => {
                console.error('Erro ao copiar:', err);
                button.innerHTML = '<i class="fa-solid fa-exclamation-circle"></i>';
            });
        }
    },
    mounted() {
        this.fetchArtigo();
        this.$nextTick(() => {
            this.executarScripts();
        });

        window.copyCode = this.copyCode;
    }
};
</script>

<style scoped>
/* Oculta elementos até o Vue carregar completamente */
[v-cloak] {
    display: none;
}

@media (max-width: 768px) {
    .subtitle {
        display: none;
    }
}

/* Mantemos o hero com min-height para evitar shift */
.hero {
    min-height: 300px;
}

/* Removendo bordas e outros estilos conflitantes */
.hljs,
pre.hljs,
code.hljs {
    border: none !important;
    /* Remove quaisquer bordas */
    box-shadow: none !important;
    /* Remove sombras */
    margin: 0 !important;
    /* Remove margens */
    padding: 0 !important;
    /* Remove padding */
    border-radius: 0 !important;
    /* Remove bordas arredondadas */
    background: #2d2d2d !important;
    /* Mantém fundo escuro */
    color: #f8f8f2 !important;
    /* Define cor padrão do texto */
}
</style>

<style>
/* Prevenindo estilos extras no <code> */
code {
    border: none !important;
    box-shadow: none !important;
    margin: 0 !important;
    padding: 0 !important;
    background: none !important;
}

/* Mantém o estilo aqui, mas sem o atributo scoped */
.code-container {
    position: relative;
    margin-bottom: 1em;
}

.copy-btn {
    position: absolute;
    top: 10px;
    right: 10px;
    background: #333;
    color: #fff;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    cursor: pointer;
    font-size: 12px;
    transition: background 0.3s;
}

.copy-btn:hover {
    background: #555;
}

pre {
    background-color: hsl(221, 14%, 14%) !important;
}
</style>