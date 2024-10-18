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
import { tsParticles } from '@tsparticles/engine';
import { loadFull } from 'tsparticles';

export default {
    setup() {
        const particlesSection = ref(null); // Referência para a seção onde as partículas serão aplicadas

        const initParticles = async () => {
            if (particlesSection.value) {
                particlesSection.value.setAttribute('id', 'section_particulas'); // Definir o id
            }

            try {
                await loadFull(tsParticles);
                await tsParticles.load({
                    id: 'section_particulas',
                    options: {
                        autoPlay: true,
                        background: {
                            color: { value: '#212529' },
                        },
                        fullScreen: { enable: true, zIndex: -200 },
                        detectRetina: true,
                        fpsLimit: 120,
                        interactivity: {
                            detectsOn: 'window',
                            events: {
                                onClick: { enable: true, mode: 'push' },
                                onHover: { enable: true, mode: 'repulse' },
                                resize: { enable: true },
                            },
                            modes: {
                                push: { quantity: 4 },
                                repulse: { distance: 200, duration: 0.4 },
                            },
                        },
                        particles: {
                            color: { value: '#ff0000' },
                            move: { enable: true, speed: 6 },
                            number: { value: 80, density: { enable: true, width: 1920, height: 1080 } },
                            opacity: { value: 0.5 },
                            size: { value: { min: 1, max: 3 } },
                            links: {
                                enable: true,
                                distance: 150,
                                color: '#ffffff',
                                opacity: 0.4,
                                width: 1,
                            },
                        },
                    },
                });
            } catch (error) {
                console.error('Erro ao carregar partículas:', error);
            }
        };

        onMounted(() => {
            initParticles(); // Inicializar partículas ao montar o componente
        });

        onBeforeUnmount(() => {
            tsParticles.dom().forEach((container) => {
                container.stop(); // Parar as partículas ao desmontar o componente
            });
        });

        return {
            particlesSection,
        };
    },
};
</script>