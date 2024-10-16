import axios from 'axios';
import { alerts } from './alerts/alerts';

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
            const response = await axios.post('/api/contato', mensagem);
            alerts.off();

            if (response.status === 200) {
                formulario.reset();
                alerts._({ tipo: 'check', mensagem: 'Mensagem foi enviada com sucesso' });
            } else {
                alerts._({ tipo: 'erro', mensagem: 'Erro! Marque a caixa "Não sou um robô"' });
            }
        } catch (error) {
            console.error('Erro na requisição:', error);
        }
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    new FormularioContato();

    const inputmaskModule = await import("inputmask");
    const Inputmask = inputmaskModule.default;

    if (window.location.pathname === '/contato') {
        const telefoneInput = document.querySelector('input[name="telefone"]') as HTMLInputElement;
        const telefoneMask = new Inputmask({
            mask: '(99) 9999-9999',
            placeholder: '_',
        });

        telefoneMask.mask(telefoneInput);
    }
});
