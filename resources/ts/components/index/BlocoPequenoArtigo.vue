<template>
    <div class="column is-4">
        <a :href="getArticleUrl(artigo.url)">
            <div class="box article">
                <article>
                    <figure class="image is-3by2">
                        <img :src="artigo.imagem" :alt="`Imagem de ${artigo.artigo}`" />
                    </figure>
                    <div class="content">
                        <h3 class="title is-4">{{ artigo.artigo }}</h3>
                        <p>{{ artigo.descricao }}</p>
                        <p>Por: {{ artigo.autor }}</p>
                        <p>📅 {{ formatDate(artigo.data_publicacao) }}</p>
                    </div>
                </article>
            </div>
        </a>
    </div>
</template>

<script>
export default {
    name: 'BlocoPequenoArtigo',
    props: {
        artigo: Object,
    },
    methods: {
        getArticleUrl(url) {
            return `/artigo/${url}`;
        },
        formatDate(date) {
            const options = { year: 'numeric', month: 'long', day: 'numeric' };
            return new Date(date).toLocaleDateString('pt-BR', options);
        },
    },
};
</script>

<style scoped>
.article img {
    width: 100%;
    height: auto;
}
</style>