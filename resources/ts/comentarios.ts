import Swal from 'sweetalert2';
import axios from 'axios';

/**
 * Estrutura de um comentário retornado
 */
interface ComentarioRetornado {
    erro: string | boolean;
    nome: string;
    comentario: string;
}

/**
 * Estrutura de um comentário enviado
 */
interface Comentario {
    nome: string;
    email: string;
    comentario: string;
    url: string;
    id_comentario_resposta: number;
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
        this.iniciaForm();
    }

    /**
     * Vincula o envio do formulário ao método enviarFormulario.
     * 
     * @return {void}
     */
    private iniciaForm() {
        const formulario = document.getElementById('comentarioForm') as HTMLFormElement | null;

        if (formulario) {
            formulario.addEventListener('submit', (event) => {
                event.preventDefault();
                this.enviarFormulario();
                formulario.reset();

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
            this.mostrarSweetAlertComAnimacao(this.isDark());
            const url = this.obterSlugDoArtigo();

            const comentario: Comentario = {
                nome: this.inputNome.value,
                email: this.inputEmail.value,
                comentario: this.inputComentario.value,
                url: url ? url : '',
                id_comentario_resposta: 0
            };

            try {
                this.mostrarSweetAlertComAnimacao(this.isDark());
                const response = await axios.post('/api/comentarios', comentario);
                this.fecharSweetAlert();
                const novoComentario = response.data;

                if (novoComentario.erro) {
                    this.mostrarSweetAlert('Erro ao enviar comentário!', false, this.isDark());
                    return;
                }

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
    private atualizarInterface(novoComentario: ComentarioRetornado) {
        const caixaComentarios = document.querySelector('.bloco-comentarios');

        if (caixaComentarios) {
            const templateComentario = `
                <div class="box">
                    <article class="media">
                        <div class="media-content">
                            <div class="content">
                                <strong>
                                    <p>${novoComentario.nome}</p>
                                </strong>
                                <p>${novoComentario.comentario}</p>
                            </div>
                        </div>
                    </article>
                </div>`;

            caixaComentarios.insertAdjacentHTML('afterbegin', templateComentario);
        }
    }

    /**
     * Obtém o slug do artigo a partir da URL.
     * 
     * @returns {string | null} O slug do artigo ou null se não encontrado.
     */
    private obterSlugDoArtigo(): string | null {
        const url = window.location.href;

        const regex = /\/artigo\/([a-zA-Z0-9-]+)/;
        const correspondencia = url.match(regex);

        if (correspondencia && correspondencia.length > 1) {
            return correspondencia[1];
        } else {
            return null;
        }
    }

    /**
     * Exibe um SweetAlert com ícone sinalizando aguardo
     *
     * @param {boolean} temaDark - Indica se o tema deve ser escuro (dark).
     * @return {void}
     */
    private mostrarSweetAlertComAnimacao(temaDark: boolean): void {
        Swal.fire({
            title: "Aguarde...",
            allowOutsideClick: false,
            allowEscapeKey: false,
            showConfirmButton: false,
            didOpen: () => {
                Swal.showLoading();
            },
            ...(temaDark && { backdrop: '#212529', customClass: { container: 'dark-mode' } }),
        });
    };

    /**
     * Remove o SweetAlert da tela
     *
     * @return {void}
     */
    private fecharSweetAlert(): void {
        Swal.close();
    };

    /**
     * Exibe um SweetAlert com um ícone indicando sucesso ou erro, com base nos parâmetros fornecidos.
     *
     * @param {string} mensagem - A mensagem a ser exibida no SweetAlert.
     * @param {boolean} sucesso - Indica se o SweetAlert deve exibir um ícone de sucesso ou erro.
     * @param {boolean} temaDark - Indica se o tema deve ser escuro (dark).
     * @return {void}
     */
    private mostrarSweetAlert(mensagem: string, sucesso: boolean, temaDark: boolean): void {
        Swal.fire({
            icon: sucesso ? 'success' : 'error',
            title: sucesso ? 'Sucesso' : 'Erro',
            text: mensagem,
            backdrop: temaDark ? '#212529' : '#fff',
            customClass: {
                container: temaDark ? 'dark-mode' : '',
            },
            showConfirmButton: false,
            timer: 3000,
        });
    };

    /**
     * Verifica se o tema atual é escuro (dark).
     *
     * @returns {boolean} Retorna true se o tema for escuro; false, caso contrário.
     */
    private isDark(): boolean {
        return Boolean(window.matchMedia('(prefers-color-scheme: dark)').matches);
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ComentarioHandler();
});