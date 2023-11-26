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
