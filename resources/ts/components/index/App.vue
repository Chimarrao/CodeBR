<template>
    <Menu @pesquisar="pesquisar" />
    <Cabecalho />

    <section class="section index">
        <div class="container">
            <div v-if="!searchQuery">
                <h2 class="title is-3">Artigos em Destaque</h2>
                <div class="columns is-multiline">
                    <BlocoPequenoArtigo v-for="artigo in artigosDestaque" :key="index" :artigo="artigo" />
                </div>
            </div>

            <h2 class="title is-3">
                {{ searchQuery ? 'Resultados da busca' : 'Artigos Recentes' }}
            </h2>
            <div class="columns is-multiline">
                <SkeletonLoader v-if="loading" v-for="i in 9" :key="i" />
                <BlocoPequenoArtigo v-else v-for="artigo in artigos" :key="index" :artigo="artigo" />
            </div>

            <div class="container">
                <nav class="pagination is-centered" role="navigation" aria-label="pagination">
                    <a v-if="numeroPagina > 1" class="pagination-previous"
                        @click.prevent="navigateToPage(numeroPagina - 1)">
                        Anterior
                    </a>

                    <ul class="pagination-list">
                        <li v-for="i in paginationRange" :key="i">
                            <a :class="['pagination-link', { 'is-current': i === numeroPagina }]"
                                @click.prevent="navigateToPage(i)">
                                {{ i }}
                            </a>
                        </li>
                    </ul>

                    <a v-if="numeroPagina < totalPaginas" class="pagination-next"
                        @click.prevent="navigateToPage(numeroPagina + 1)">
                        Próxima
                    </a>
                </nav>
            </div>
        </div>
    </section>

    <Rodape />
</template>

<script>
import Menu from './../Menu.vue';
import Rodape from './../Rodape.vue';
import Cabecalho from './../Cabecalho.vue';
import BlocoPequenoArtigo from './BlocoPequenoArtigo.vue';
import SkeletonLoader from './SkeletonLoader.vue';

export default {
    components: {
        Menu,
        Rodape,
        Cabecalho,
        BlocoPequenoArtigo,
        SkeletonLoader
    },
    data() {
        return {
            artigosDestaque: [],
            artigos: [],
            numeroPagina: 1,
            totalPaginas: 0,
            artigosPorPagina: 9,
            searchQuery: '',
            loading: true,
            texto: 'Um blog sobre programação',
            index: 0
        };
    },
    beforeRouteUpdate(to, from, next) {
        if (to.query.q !== from.query.q || to.params.page !== from.params.page) {
            this.numeroPagina = parseInt(to.params.page) || 1;
            this.searchQuery = to.query.q || '';

            this.fetchArtigos();
        }
        next();
    },
    mounted() {
        this.iniciarDigitacao();

        window.addEventListener('popstate', this.handlePopState);

        const q = new URLSearchParams(window.location.search).get('q');
        const page = window.location.pathname.split('/').pop();

        if (q) {
            this.searchQuery = q;
        }

        if (page) {
            this.numeroPagina = parseInt(page);
        }

        this.fetchArtigosDestaque();
        this.fetchArtigos();
    },
    beforeDestroy() {
        window.removeEventListener('popstate', this.handlePopState);
    },
    computed: {
        paginationRange() {
            return Array.from({ length: 5 }, (_, i) => {
                const start = Math.max(1, this.numeroPagina - 2);
                return start + i;
            }).filter(pagina => pagina <= this.totalPaginas);
        },
    },
    methods: {
        iniciarDigitacao() {
            if (this.index < this.texto.length) {
                const subtitulo = document.getElementById('subtitulo');
                if (subtitulo) {
                    subtitulo.textContent += this.texto[this.index];
                    this.index++;
                    setTimeout(this.iniciarDigitacao, 50);
                }
            }
        },

        /**
         * Retorna a URL da paginação
         * 
         * @return {string}
         */
        getPaginationUrl(page) {
            const query = this.searchQuery ? `&q=${encodeURIComponent(this.searchQuery)}` : '';
            return `/page/${page}?q=${query}`;
        },

        /**
         * Navega para uma nova página sem recarregar a página inteira
         * Atualiza a URL e os artigos dinamicamente
         *
         * @param {number} page - Número da página
         */
        navigateToPage(page) {
            const url = this.getPaginationUrl(page);
            window.history.pushState({}, '', url);
            this.numeroPagina = page;
            this.fetchArtigos();
        },

        /**
         * Manipula a navegação via botão "voltar" ou "avançar" do navegador
         */
        handlePopState() {
            const q = new URLSearchParams(window.location.search).get('q');
            const page = window.location.pathname.split('/').pop();

            if (q) {
                this.searchQuery = q;
            }

            if (page) {
                this.numeroPagina = parseInt(page);
            }

            this.fetchArtigos();
        },

        /**
         * Busca os artigos em destaque da página
         * 
         * @return {void}
         */
        async fetchArtigosDestaque() {
            try {
                const resposta = await fetch(`/api/artigos-destaque`);
                const resultado = await resposta.json();

                if (resultado.success) {
                    this.artigosDestaque = resultado.data;
                } else {
                    console.error('Erro ao carregar os artigos:', resultado.message);
                }
            } catch (error) {
                console.error('Erro ao buscar os artigos:', error);
            }
        },

        /**
         * Busca os artigos da página
         * 
         * @return {void}
         */
        async fetchArtigos() {
            this.loading = true;

            try {
                const resposta = await fetch(`/api/artigos?page=${this.numeroPagina}&q=${encodeURIComponent(this.searchQuery)}`);
                const resultado = await resposta.json();

                if (resultado.success) {
                    this.totalPaginas = resultado.total_paginas;
                    this.artigos = resultado.data;
                } else {
                    console.error('Erro ao carregar os artigos:', resultado.message);
                }
            } catch (error) {
                console.error('Erro ao buscar os artigos:', error);
            }

            this.loading = false;
        },

        /**
         * Pesquisa os artigos
         * 
         * @param {string} termo Termo da pesquisa
         */
        pesquisar(termo) {
            this.searchQuery = termo;
            this.fetchArtigos();
        }
    }
};
</script>