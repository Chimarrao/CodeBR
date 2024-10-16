<template>
    <div class="resposta-container mt-3">
        <article class="media">
            <div class="media-content">
                <div class="content">
                    <strong>
                        <p>{{ resposta.nome }}</p>
                    </strong>
                    <p>{{ resposta.comentario }}</p>
                </div>
            </div>
        </article>
    </div>
</template>

<script>
export default {
    props: {
        resposta: Object,
    },
};
</script>
