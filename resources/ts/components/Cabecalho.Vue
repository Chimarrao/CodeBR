<template>
    <section class="hero is-medium is-dark is-bold" ref="particlesSection">
        <div class="hero-body">
            <div class="container text-center">
                <h1 class="title">Code BR</h1>
                <h2 class="subtitle" id="subtitulo"></h2>
            </div>
        </div>
    </section>
</template>

<script>
import { onMounted, onBeforeUnmount, ref } from 'vue';

export default {
    setup() {
        const particlesSection = ref(null);

        const initParticles = async () => {
            if (particlesSection.value) {
                particlesSection.value.setAttribute('id', 'section_particulas');
            }
        };

        onMounted(() => {
            initParticles();
        });

        onBeforeUnmount(() => {
            // 
        });

        return {
            particlesSection,
        };
    },
};
</script>