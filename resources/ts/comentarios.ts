/**
 * Representa a estrutura de um comentário.
 */
interface Comentario {
    nome: string;
    email: string;
    comentario: string;
}

/**
 * Classe responsável por manipular os comentários em um formulário.
 */
class ComentarioHandler {
    /**
     * Elemento de entrada para o nome.
     * @type {HTMLInputElement | null}
     */
    private inputNome: HTMLInputElement | null;

    /**
     * Elemento de entrada para o e-mail.
     * @type {HTMLInputElement | null}
     */
    private inputEmail: HTMLInputElement | null;

    /**
     * Elemento de entrada para o comentário.
     * @type {HTMLTextAreaElement | null}
     */
    private inputComentario: HTMLTextAreaElement | null;

    /**
     * Construtor da classe ComentarioHandler.
     * 
     * @return {void}
     */
    constructor() {
        this.inputNome = document.querySelector<HTMLInputElement>('input[name="nome"]');
        this.inputEmail = document.querySelector<HTMLInputElement>('input[name="email"]');
        this.inputComentario = document.querySelector<HTMLTextAreaElement>('textarea[name="comentario"]');
        this.inicializarEventos();
    }

    /**
     * Inicializa os eventos, vinculando o envio do formulário ao método enviarFormulario.
     * 
     * @return {void}
     */
    private inicializarEventos() {
        const formulario = document.getElementById('comentarioForm') as HTMLFormElement | null;

        if (formulario) {
            formulario.addEventListener('submit', (event) => {
                event.preventDefault();
                this.enviarFormulario();

                return false;
            });
        }
    }

    /**
     * Envia o formulário para o backend e atualiza a interface com o novo comentário.
     * 
     * @return {void}
     */
    private async enviarFormulario() {
        if (this.inputNome && this.inputEmail && this.inputComentario) {
            const comentario: Comentario = {
                nome: this.inputNome.value,
                email: this.inputEmail.value,
                comentario: this.inputComentario.value,
            };

            try {
                const response = await fetch('/api/comentarios', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(comentario),
                });

                const novoComentario = await response.json();
                this.atualizarInterface(novoComentario);
            } catch (erro) {
                console.error('Erro ao enviar comentário:', erro);
            }
        }
    }

    /**
     * Atualiza a interface com o novo comentário.
     * 
     * @param {Comentario} novoComentario - O novo comentário a ser adicionado à interface.
     * @return {void}
     */
    private atualizarInterface(novoComentario: Comentario) {
        const caixaComentarios = document.querySelector('.box');

        if (caixaComentarios) {
            const templateComentario = `
                <article class="media">
                    <div class="media-content">
                        <div class="content">
                            <strong>
                                <p>${novoComentario.nome}</p>
                            </strong>
                            <p>${novoComentario.comentario}</p>
                        </div>
                    </div>
                </article>`;

            caixaComentarios.insertAdjacentHTML('afterbegin', templateComentario);
        }
    }
}

new ComentarioHandler();