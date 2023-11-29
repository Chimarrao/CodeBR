import Swal from 'sweetalert2';

/**
 * Exibe um SweetAlert com ícone sinalizando aguardo
 *
 * @param {boolean} temaDark - Indica se o tema deve ser escuro (dark).
 * @return {void}
 */
export function mostrarSweetAlertComAnimacao(temaDark: boolean): void {
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
 * Exibe um SweetAlert com um ícone indicando sucesso ou erro, com base nos parâmetros fornecidos.
 *
 * @param {string} mensagem - A mensagem a ser exibida no SweetAlert.
 * @param {boolean} sucesso - Indica se o SweetAlert deve exibir um ícone de sucesso ou erro.
 * @param {boolean} temaDark - Indica se o tema deve ser escuro (dark).
 * @return {void}
 */
export function mostrarSweetAlert(mensagem: string, sucesso: boolean, temaDark: boolean): void {
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
 * Remove o SweetAlert da tela
 *
 * @return {void}
 */
export function fecharSweetAlert(): void {
    Swal.close();
};

/**
 * Verifica se o tema atual é escuro (dark).
 *
 * @returns {boolean} Retorna true se o tema for escuro; false, caso contrário.
 */
export function isDark(): boolean {
    return Boolean(window.matchMedia('(prefers-color-scheme: dark)').matches);
}