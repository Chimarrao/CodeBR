import * as util from './util/util';

/**
 * Estrutura de um comentário retornado
 */
interface ComentarioRetornado {
    id: number;
    erro: string | boolean;
    nome: string;
    comentario: string;
    id_comentario_resposta: number;
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
    g_recaptcha_response: string;
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
        this.inciarFormsResposta();
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
                const nome = this.inputNome?.value as string;
                const email = this.inputEmail?.value as string;
                const comentario = this.inputComentario?.value as string;
                const recaptchaElement = document.querySelector('textarea[name="g-recaptcha-response"]');
                const recaptcha = recaptchaElement instanceof HTMLTextAreaElement ? recaptchaElement.value : '';

                this.enviarFormulario(formulario, nome, email, comentario, recaptcha);

                return false;
            });
        }
    }

    /**
     * Inicia os formulários de resposta para os comentários.
     * Adiciona listeners de eventos aos botões de resposta e formulários correspondentes.
     * 
     * @return {void}
     */
    private inciarFormsResposta() {
        const botoesResposta: NodeListOf<HTMLElement> = document.querySelectorAll('.button.is-link.is-small.responder');

        botoesResposta.forEach((botao: HTMLElement) => {
            botao.addEventListener('click', (event) => {
                this.toggleForm(`resposta_form_${id_comentario}`);
            });

            const id_comentario = botao.getAttribute('id_comentario') as number | null;
            const formulario = document.getElementById(`resposta_form_${id_comentario}`) as HTMLFormElement | null;

            if (formulario) {
                const nome = formulario.querySelector('input[name="nome"]') as HTMLFormElement | null;
                const email = formulario.querySelector('input[name="email"]') as HTMLFormElement | null;
                const comentario = formulario.querySelector('textarea[name="comentario"]') as HTMLFormElement | null;

                formulario.addEventListener('submit', (event) => {
                    const recaptchaElement = formulario.querySelector('textarea[name="g-recaptcha-response"]');
                    const recaptcha = recaptchaElement instanceof HTMLTextAreaElement ? recaptchaElement.value : '';

                    event.preventDefault();
                    this.enviarFormulario(formulario, nome?.value, email?.value, comentario?.value, recaptcha as string, id_comentario as number);

                    return false;
                });
            }
        });
    }

    /**
     * Envia o formulário para o backend e atualiza a interface com o novo comentário.
     * 
     * @param {HTMLFormElement} formulario Formulário
     * @param {string} nome Nome de quem está enviando
     * @param {string} email E-mail
     * @param {string} comentario Comentário
     * @param {string} g_recaptcha_response Valor do recaptcha
     * @param {number} id_comentario_resposta Id do comentário respondido (0 para novo comentário)
     * @return {void}
     */
    private async enviarFormulario(formulario: HTMLFormElement, nome: string, email: string, comentario: string, g_recaptcha_response: string, id_comentario_resposta: number = 0) {
        util.mostrarSweetAlertComAnimacao(util.isDark());
        const url = this.obterSlugDoArtigo();

        const comentarioEnviar: Comentario = {
            nome: nome,
            email: email,
            comentario: comentario,
            url: url ? url : '',
            id_comentario_resposta: id_comentario_resposta,
            g_recaptcha_response: g_recaptcha_response
        };

        try {
            const axiosModule = await import(/* webpackChunkName: "axios" */'axios');
            const axios = axiosModule.default;

            util.mostrarSweetAlertComAnimacao(util.isDark());
            const response = await axios.post('/api/comentarios', comentarioEnviar);
            util.fecharSweetAlert();
            const novoComentario = response.data;

            if (novoComentario.erro) {
                util.mostrarSweetAlert('Erro ! Marque a caixa "Não sou um robô"', false, util.isDark());
                util.fecharSweetAlert();
                return;
            }

            this.atualizarInterface(novoComentario);
            formulario.reset();
            formulario.style.display = 'none';
        } catch (erro) {
            console.error('Erro :', erro);
            util.mostrarSweetAlert('Erro ! Marque a caixa "Não sou um robô"', false, util.isDark());
        }
    }

    /**
     * Atualiza a interface com o novo comentário.
     * 
     * @param {Comentario} novoComentario - O novo comentário a ser adicionado à interface.
     * @return {void}
     */
    private atualizarInterface(novoComentario: ComentarioRetornado) {
        if (!novoComentario.id_comentario_resposta) {
            const caixaComentarios = document.querySelector('.bloco-comentarios');

            if (caixaComentarios) {
                caixaComentarios.insertAdjacentHTML('afterbegin',
                    `<div class="box">
                        <article class="media">
                            <div class="media-content">
                                <div class="content">
                                    <strong>
                                        <p>${novoComentario.nome}</p>
                                    </strong>
                                    <p>${novoComentario.comentario}</p>
                                </div>
                                <button class="button is-link is-small responder" id_comentario="${novoComentario.id}">Responder</button>
                                <form id="resposta_form_${novoComentario.id}" method="POST" style="display: none">
                                    <div class="field is-horizontal">
                                        <div class="field-body">
                                            <div class="field">
                                                <p class="label">Nome:</p>
                                                <div class="control">
                                                    <input class="input" name="nome" type="text" required>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <p class="label">Email (não será publicado):</p>
                                                <div class="control">
                                                    <input class="input" name="email" type="email">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <p class="label">Comentário:</p>
                                        <div class="control">
                                            <textarea class="textarea" name="comentario" required></textarea>
                                        </div>
                                    </div>

                                    <div class="field">
                                        <div class="g-recaptcha" data-sitekey="6LdlmB4pAAAAAF8uCw8BeWogDClVSiCRx5eNx-7e"></div>
                                    </div>

                                    <div class="control">
                                        <button class="button is-primary" type="submit">Enviar Comentário</button>
                                    </div>
                                </form>
                            </div>
                        </article>

                        <div id="respostas_${novoComentario.id}">
                        </div>
                    </div>`
                );

                this.inciarFormsResposta();
            }
        } else {
            const caixaResposatas = document.getElementById(`respostas_${novoComentario.id_comentario_resposta}`);

            if (caixaResposatas) {
                caixaResposatas.insertAdjacentHTML('beforeend',
                    `<div class="resposta-container mt-3">
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
                    </div>`
                );
            }
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
     * Alternar a visibilidade de um formulário entre display: none e display: block.
     * 
     * @param {string} formId - O ID do elemento do formulário.
     * @returns {void}
     */
    private toggleForm(formId: string): void {
        const form = document.getElementById(formId);

        if (form) {
            form.style.display = form.style.display === 'none' ? '' : 'none';
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new ComentarioHandler();
});