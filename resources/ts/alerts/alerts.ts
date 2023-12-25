/**
 * Funções globais
 */
class main {
    /**
     * Bloqueia a rolagem da página.
     * 
     * @return {void}
     */
    protected bloquearRolagem() {
        document.body.style.overflow = 'hidden';
        document.documentElement.style.overflow = 'hidden';
    }

    /**
     * Desbloqueia a rolagem da página.
     * 
     * @return {void}
     */
    protected desbloquearRolagem() {
        document.body.style.overflow = '';
        document.documentElement.style.overflow = '';
    }

    /**
     * Estiliza o fundo escuro.
     * 
     * @param darkBackground Elemento de fundo escuro a ser estilizado.
     * @return {void}
     */
    protected setFundoEscuro(darkBackground: HTMLElement) {
        darkBackground.style.backgroundColor = 'rgba(0, 0, 0, 0.5)';
        darkBackground.style.position = 'fixed';
        darkBackground.style.top = '0';
        darkBackground.style.left = '0';
        darkBackground.style.width = '100%';
        darkBackground.style.height = '100%';
        darkBackground.style.zIndex = '1000';
    }

    /**
     * Para a exibição do alert
     * 
     * @return {void}
     */
    public parar() {
        const divLoading = document.getElementById('carregamentoContainer') as HTMLDivElement;
        const darkBackground = document.querySelector('.fundo-escuro') as HTMLElement;

        if (divLoading) {
            divLoading.remove();
        }

        if (darkBackground) {
            darkBackground.remove();
        }

        this.desbloquearRolagem();
    }
}

/**
 * Spinner
 */
class spinner extends main {
    /**
     * Exibe o spinner de carregamento.
     * 
     * @param mensagem Mensagem a ser exibida junto ao spinner.
     * @return {void}
     */
    public exibirSpinner(mensagem: string) {
        const darkBackground = document.createElement('div');
        darkBackground.classList.add('fundo-escuro');
        document.body.insertAdjacentElement('beforeend', darkBackground);

        const div = document.createElement('div');
        div.innerHTML = `
            <div class="container-carregamento" id="carregamentoContainer">
                <div class="carregamento-spinner" id="carregamentoSpinner"></div>
                <p class="carregamento-msg">${mensagem}</p>
            </div>`;
        document.body.insertAdjacentElement('beforeend', div);

        this.setFundoEscuro(darkBackground);
        this.estilizarAnimacao();

        this.bloquearRolagem();
    }

    /**
     * Estiliza a animação do spinner.
     * 
     * @return {void}
     */
    private estilizarAnimacao() {
        const divLoading = document.getElementById('carregamentoContainer') as HTMLDivElement;
        const spinner = document.getElementById('carregamentoSpinner') as HTMLDivElement;

        if (divLoading) {
            divLoading.style.display = 'flex';
        }
    }
}

/**
 * Checkok
 */
class checkOk extends spinner {
    public exibirCheck(mensagem: string) {
        const darkBackground = document.createElement('div');
        darkBackground.classList.add('fundo-escuro');
        document.body.insertAdjacentElement('beforeend', darkBackground);

        const div = document.createElement('div');
        div.innerHTML = `
            <div class="container-carregamento" id="carregamentoContainer">
                <div class="sucesso-check">
                    <div class="icone-check">
                        <span class="linha-icon line-tip"></span>
                        <span class="linha-icon line-long"></span>
                        <div class="circulo-icon"></div>
                        <div class="correcao-icon"></div>
                    </div>
                </div>
                <p class="carregamento-msg">${mensagem}</p>
                <button class="botao-ok">OK</button>
            </div>`;
        document.body.insertAdjacentElement('beforeend', div);
        this.setFundoEscuro(darkBackground);
        this.bloquearRolagem();

        document.querySelector('.botao-ok')?.addEventListener('click', () => {
            alerts.off();
        });
    }
}

/**
 * Erro
 */
class erro extends checkOk {
    public exibirErro(mensagem: string) {
        const darkBackground = document.createElement('div');
        darkBackground.classList.add('fundo-escuro');
        document.body.insertAdjacentElement('beforeend', darkBackground);

        const div = document.createElement('div');
        div.innerHTML = `
            <div class="container-carregamento" id="carregamentoContainer">
                <div class="borda-circulo"></div>
                    <div class="circulo">
                        <div class="erro"></div>
                    </div>
                    <p class="carregamento-msg">${mensagem}</p>
                    <button class="botao-ok">OK</button>
                </div>
            </div>`;
        document.body.insertAdjacentElement('beforeend', div);
        this.setFundoEscuro(darkBackground);
        this.bloquearRolagem();

        document.querySelector('.botao-ok')?.addEventListener('click', () => {
            alerts.off();
        });
    }
}

export class alerts extends erro {
    static _(config: any) {
        const obj = new alerts();
        const mensagem = config.mensagem ?? '';

        switch (config.tipo) {
            case 'spinner':
                obj.exibirSpinner(mensagem || 'Aguarde');
                break;
            case 'check':
                obj.exibirCheck(mensagem || 'Ok');
                break;
            case 'erro':
                obj.exibirErro(mensagem || 'Erro');
                break;
            default:
                break;
        }
    }

    static off() {
        const obj = new alerts();
        obj.parar();
    }
}