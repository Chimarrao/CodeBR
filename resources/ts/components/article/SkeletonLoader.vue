<template>
    <section class="hero is-medium is-dark is-bold skeleton-hero">
        <div class="hero-body">
            <div class="container text-center">
                <div class="columns is-centered">
                    <div class="column is-two-thirds">
                        <h1 class="skeleton-title skeleton-artigo-title"></h1>
                        <h2 class="skeleton-subtitle skeleton-artigo-subtitle"></h2>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section artigo skeleton-article-section">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-one">
                    <!-- Coluna vazia -->
                </div>
                <div class="column is-two-thirds">
                    <div class="skeleton-text-line"></div>
                    <div class="skeleton-text-line"></div>
                    <div class="skeleton-text-line"></div>
                    <div class="skeleton-text-line"></div>
                    <div class="skeleton-text-line"></div>
                </div>
                <div class="column is-one">
                    <!-- Coluna vazia -->
                </div>
            </div>
        </div>
    </section>

    <div class="section comentarios pt-0 skeleton-comments-section">
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-two-thirds">
                    <h2 class="skeleton-title skeleton-comments-title"></h2>
                    <div class="skeleton-comment"></div>
                    <div class="skeleton-comment"></div>
                    <div class="skeleton-comment"></div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "SkeletonLoader",
};
</script>

<style scoped>
.skeleton-hero {
    background-color: #e0e0e0;
}

.skeleton-title {
    height: 2.5rem;
    background-color: #e0e0e0;
    margin-bottom: 1rem;
    border-radius: 4px;
}

.skeleton-subtitle {
    height: 1.5rem;
    background-color: #e0e0e0;
    margin-bottom: 1rem;
    border-radius: 4px;
}

.skeleton-article-section {
    padding-top: 20px;
}

.skeleton-text-line {
    height: 1rem;
    background-color: #e0e0e0;
    margin-bottom: 10px;
    border-radius: 4px;
}

.skeleton-comments-section {
    padding-top: 20px;
}

.skeleton-comments-title {
    height: 2rem;
    background-color: #e0e0e0;
    margin-bottom: 1.5rem;
    border-radius: 4px;
}

.skeleton-comment {
    height: 2.5rem;
    background-color: #e0e0e0;
    margin-bottom: 15px;
    border-radius: 4px;
}
</style>
