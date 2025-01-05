<template>
    <section v-if="!$route.path.includes('/artigo/')" class="hero is-medium is-dark is-bold" ref="particlesSection">
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

        const isMobile = () => window.innerWidth <= 768;

        const initParticles = async () => {
            if (particlesSection.value) {
                particlesSection.value.setAttribute('id', 'section_particulas');
            }

            const hero = document.querySelector('.hero');
            if (hero instanceof HTMLElement) {
                hero.setAttribute('id', 'section_particulas');
            }

            try {
                const mobileDevice = isMobile();
                const particleCount = mobileDevice ? 20 : 40;

                await tsParticles.load('section_particulas', {
                    autoPlay: true,
                    background: {
                        color: { value: '#212529' },
                        opacity: 1,
                    },
                    fullScreen: {
                        enable: true,
                        zIndex: -200,
                    },
                    detectRetina: false,
                    fpsLimit: 60,
                    interactivity: {
                        detectsOn: 'window',
                        events: {
                            onClick: { enable: isMobile ? false : true, mode: 'push' },
                            onHover: {
                                enable: true,
                                mode: 'repulse',
                            },
                            resize: { enable: isMobile ? false : true, delay: 0.5 },
                        },
                    },
                    particles: {
                        number: { value: particleCount },
                        color: {
                            value: '#ff0000',
                            animation: {
                                h: {
                                    enable: true,
                                    speed: 20,
                                    sync: true,
                                },
                            },
                        },
                        opacity: { value: 0.5 },
                        size: { value: { min: 1, max: 3 } },
                        move: {
                            enable: true,
                            speed: 6,
                            direction: 'none',
                            outModes: { default: 'out' },
                        },
                        links: {
                            enable: true,
                            distance: 150,
                            color: '#ffffff',
                            opacity: 0.4,
                            width: 1,
                        },
                    },
                    pauseOnBlur: true,
                    pauseOnOutsideViewport: true,
                    zLayers: 100,
                });
            } catch (error) {
                console.error('Erro ao carregar os módulos:', error);
            }
        };

        onMounted(() => {
            if (isMobile) {
                setTimeout(() => {
                    initParticles();
                }, 1000);
            } else {
                initParticles();
            }
        });

        onBeforeUnmount(() => {
        });

        return {
            particlesSection,
        };
    },
};
</script>