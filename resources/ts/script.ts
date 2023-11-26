/**
 * Classe responsável por realizar a animação de digitação em um elemento HTML.
 */
class DigitarTexto {
    private subtituloElemento: HTMLElement | null;
    private texto: string;
    private local: HTMLElement;
    private index: number;

    /**
     * Cria uma instância de DigitarTexto.
     * 
     * @return {void}
     */
    constructor() {
        this.subtituloElemento = document.getElementById('subtitulo');
        this.texto = 'Um blog sobre programação';
        this.local = this.subtituloElemento as HTMLElement;
        this.index = 0;

        this.iniciarDigitacao();
    }

    /**
     * Função privada que realiza a digitação do texto no elemento HTML.
     * 
     * @return {void}
     */
    private digitar(): void {
        if (this.subtituloElemento) {
            this.local.textContent += this.texto[this.index];
            this.index++;

            if (this.index < this.texto.length) {
                setTimeout(() => this.digitar(), 50);
            }
        }
    }

    /**
     * Inicia o processo de digitação do texto.
     * 
     * @return {void}
     */
    public iniciarDigitacao(): void {
        this.digitar();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new DigitarTexto();
});


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

/**
 * Classe responsável por manipular o menu de hambúrguer.
 */
class MenuHamburguer {
    private itensMenu: HTMLElement[];

    /**
     * Cria uma instância de MenuHamburguer.
     * 
     * @return {void}
     */
    constructor() {
        this.itensMenu = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);
        this.inicializarMenu();
    }

    /**
     * Alterna a classe 'is-active' para exibir ou ocultar um elemento.
     * 
     * @param {HTMLElement} elemento - O elemento HTML alvo.
     * @return {void}
     */
    private alternarVisibilidade(elemento: HTMLElement): void {
        elemento.classList.toggle('is-active');
    }

    /**
     * Manipula o clique no menu de hambúrguer.
     * 
     * @param {HTMLElement} elemento - O elemento HTML do menu de hambúrguer clicado.
     * @return {void}
     */
    private lidarComCliqueMenu(elemento: HTMLElement): void {
        const idAlvo = elemento.dataset.target;
        if (idAlvo) {
            const itemAlvo = document.getElementById(idAlvo);
            if (itemAlvo) {
                this.alternarVisibilidade(elemento);
                this.alternarVisibilidade(itemAlvo);
            }
        }
    }

    /**
     * Inicializa o menu de hambúrguer configurando os eventos de clique.
     * 
     * @return {void}
     */
    private inicializarMenu(): void {
        if (this.itensMenu.length > 0) {
            this.itensMenu.forEach(elemento => {
                elemento.addEventListener('click', () => this.lidarComCliqueMenu(elemento));
            });
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new MenuHamburguer();
});
