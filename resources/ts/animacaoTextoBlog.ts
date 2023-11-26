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