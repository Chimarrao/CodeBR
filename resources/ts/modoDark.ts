
/**
 * Classe responsável por aplicar estilos relacionados ao modo escuro.
 */
class EstilizadorModoEscuro {
    /**
     * Elemento contêiner onde os estilos serão aplicados.
     */
    private elementoConteiner: HTMLElement | null;

    /**
     * Cria uma instância do EstilizadorModoEscuro.
     * 
     * @return {void}
     */
    constructor() {
        this.elementoConteiner = document.querySelector('.section.artigo');
        this.aplicarEstilosModoEscuro();
    }

    /**
     * Aplica estilos a elementos específicos.
     * 
     * @param {NodeListOf<Element>} elementos - Lista de elementos a serem estilizados.
     * @return {void}
     */
    private aplicarEstilosAElementos(elementos: NodeListOf<Element>): void {
        elementos.forEach((elemento) => {
            if (!elemento.closest('.gist')) {
                if (elemento instanceof HTMLElement) {
                    elemento.style.color = 'white';
                }
            }
        });
    }

    /**
     * Aplica estilos de cor a elementos que têm estilo "color: black;".
     * 
     * @return {void}
     */
    private aplicarEstilosCorParaElementosPretos(): void {
        const elementosPretos = document.querySelectorAll('[style="color: black;"]');
        elementosPretos.forEach((elemento) => {
            if (elemento instanceof HTMLElement) {
                elemento.style.color = 'white';
            }
        });
    }

    /**
     * Aplica estilos de cor white a spans "color: black;" na página de política de privacidade.
     * 
     * @return {void}
     */
    private aplicaEstilosPoliticaDePrivacidade(): void {
        if (window.location.pathname === '/politica-de-privacidade') {
            const spans = document.querySelectorAll('span[style="color: black;"]');

            spans.forEach(span => {
                if (span instanceof HTMLSpanElement) {
                    span.style.color = 'white';
                }
            });

            const links = document.querySelectorAll('a');

            links.forEach(link => {
                const computedStyle = window.getComputedStyle(link);
                const color = computedStyle.getPropertyValue('color');

                if (color.toLowerCase() === 'rgb(0, 0, 0)' || color.toLowerCase() === 'rgb(33, 37, 41)') {
                    if (link instanceof HTMLAnchorElement) {
                        link.style.color = 'white';
                    }
                }
            });
        }
    }

    /**
     * Aplica estilos relacionados ao modo escuro.
     * 
     * @return {void}
     */
    public aplicarEstilosModoEscuro(): void {
        if (window.matchMedia('(prefers-color-scheme: dark)').matches && this.elementoConteiner) {
            const paragrafosESpans = this.elementoConteiner.querySelectorAll('p, span, strong');

            this.aplicarEstilosAElementos(paragrafosESpans);
            this.aplicarEstilosCorParaElementosPretos();
        }

        this.aplicaEstilosPoliticaDePrivacidade();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new EstilizadorModoEscuro();
});