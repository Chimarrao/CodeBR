<template>
    <Menu />
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
                <div class="column is-one">
                    <!--  -->
                </div>
                <div class="column is-two-thirds" v-html="artigo.texto"></div>
                <div class="column is-one">
                    <!--  -->
                </div>
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

export default {
    components: {
        Menu,
        Rodape,
        Cabecalho,
        FormComentario, 
        Comentario, 
        SkeletonLoader
    },
    data() {
        return {
            artigo: [],
            comentarios: [],
            loading: false,
        };
    },
    mounted() {
        this.fetchArtigo();
    },
    methods: {
        /**
         * Busca o artigo da página
         *
         * @return {void}
         */
        async fetchArtigo() {
            this.loading = true;
            const slug = window.location.pathname.split("/artigo/")[1].split("/")[0];

            try {
                const resposta = await fetch(`/api/artigo?slug=${slug}`);
                const resultado = await resposta.json();

                if (resultado.success) {
                    this.artigo = resultado.data.artigo;
                    this.comentarios = resultado.data.comentarios;
                } else {
                    console.error("Erro ao carregar os artigos:", resultado.message);
                }
            } catch (error) {
                console.error("Erro ao buscar os artigos:", error);
            }

            this.loading = false;
        },

        adicionarComentario(novoComentario) {
            console.log("Comentário adicionado", novoComentario);
            this.fetchArtigo();
        }
    },
};
</script>
