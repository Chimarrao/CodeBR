
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
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new EstilizadorModoEscuro();
});