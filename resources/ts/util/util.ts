// #212529

/**
 * Verifica se o tema atual é escuro (dark).
 *
 * @returns {boolean} Retorna true se o tema for escuro; false, caso contrário.
 */
export function isDark(): boolean {
    return Boolean(window.matchMedia('(prefers-color-scheme: dark)').matches);
}