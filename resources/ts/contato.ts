import axios from 'axios';
import * as util from './util/util';

/**
 * Interface representando a estrutura de uma mensagem de contato.
 */
interface MensagemContato {
    nome: string;
    email: string;
    mensagem: string;
    g_recaptcha_response: string;
}

/**
 * Classe que representa o formulário de contato e suas operações.
 */
class FormularioContato {

    /**
     * Construtor da classe FormularioContato.
     */
    constructor() {
        this.iniciarFormulario();
    }

    /**
     * Inicia o formulário de contato e adiciona o evento de envio.
     * 
     * @return {void}
     */
    private iniciarFormulario() {
        const formulario = document.getElementById('contatoForm') as HTMLFormElement | null;

        if (formulario) {
            formulario.addEventListener('submit', (event) => {
                event.preventDefault();
                const nome = formulario.querySelector('input[name="nome"]') as HTMLFormElement | null;
                const email = formulario.querySelector('input[name="email"]') as HTMLFormElement | null;
                const mensagem = formulario.querySelector('textarea[name="mensagem"]') as HTMLFormElement | null;
                const recaptchaElement = document.querySelector('textarea[name="g-recaptcha-response"]');
                const recaptcha = recaptchaElement instanceof HTMLTextAreaElement ? recaptchaElement.value : '';

                const mensagemEnviar: MensagemContato = {
                    nome: nome?.value,
                    email: email?.value,
                    mensagem: mensagem?.value,
                    g_recaptcha_response: recaptcha
                };

                this.enviarFormulario(formulario, mensagemEnviar);

                return false;
            });
        }
    }

    /**
     * Envia a mensagem do formulário para o servidor.
     * 
     * @param {HTMLFormElement} formulario - O formulário HTML.
     * @param {MensagemContato} mensagem - A mensagem de contato a ser enviada.
     * @return {void}
     */
    private async enviarFormulario(formulario: HTMLFormElement, mensagem: MensagemContato): Promise<void> {
        try {
            util.mostrarSweetAlertComAnimacao(util.isDark());
            const response = await axios.post('/api/contato', mensagem);
            util.fecharSweetAlert();

            if (response.status === 200) {
                formulario.reset();
                util.mostrarSweetAlert('Mensagem foi enviada com sucesso', true, util.isDark());
            } else {
                util.mostrarSweetAlert('Erro! Marque a caixa "Não sou um robô"', false, util.isDark());
            }
        } catch (error) {
            console.error('Erro na requisição:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    new FormularioContato();
});
