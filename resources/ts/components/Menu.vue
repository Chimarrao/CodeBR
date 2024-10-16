<template>
    <nav class="navbar" role="navigation" aria-label="main navigation">
        <div class="navbar-brand">
            <a class="navbar-burger" role="button" aria-label="menu" aria-expanded="false" @click="toggleMenu">
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
                <span aria-hidden="true"></span>
            </a>
        </div>

        <div :class="['navbar-menu', { 'is-active': isActive }]" id="navbarBasicExample">
            <div class="navbar-start">
                <router-link class="navbar-item has-text-white" to="/">Início</router-link>
                <router-link class="navbar-item has-text-white" to="/sobre">Sobre</router-link>
                <router-link class="navbar-item has-text-white" to="/contato">Contato</router-link>
                <router-link class="navbar-item has-text-white" to="/politica-de-privacidade">Política de
                    Privacidade</router-link>
            </div>

            <div class="navbar-item">
                <form @submit.prevent="pesquisar">
                    <div class="field is-grouped">
                        <p class="control">
                            <input class="input" v-model="searchQuery" type="text" placeholder="Pesquisar...">
                        </p>
                        <p class="control">
                            <button class="button is-dark" type="submit">Pesquisar</button>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </nav>
</template>

<script>
export default {
    name: 'Menu',
    data() {
        return {
            isActive: false,
            searchQuery: '',
        };
    },
    methods: {
        toggleMenu() {
            this.isActive = !this.isActive;
        },
        pesquisar() {
            let queryParams = new URLSearchParams(window.location.search);

            if (this.searchQuery) {
                queryParams.set('q', this.searchQuery);
            }

            this.$router.push({ path: `/`, query: { q: this.searchQuery } });
            this.$emit('pesquisar', this.searchQuery);
        },
    },
};
</script>

<style scoped>
.navbar-item.has-text-white {
    color: white;
}
</style>