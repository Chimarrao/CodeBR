document.addEventListener('DOMContentLoaded', function () {
    if (window.matchMedia('(prefers-color-scheme: dark)').matches) {
        var containerElement = document.querySelector('.section.artigo');

        if (containerElement) {
            var paragraphsAndSpans = containerElement.querySelectorAll('p, span');

            paragraphsAndSpans.forEach(function (element) {
                element.style.color = 'white';
            });
        }
    }

    if (document.getElementById('subtitulo')) {
        const texto = 'Um blog sobre programação';
        const local = document.getElementById('subtitulo');
        let index = 0;

        function digitar() {
            local.textContent += texto[index];
            index++;

            if (index < texto.length) {
                setTimeout(digitar, 50);
            }
        }

        digitar();
    }
});

document.addEventListener('DOMContentLoaded', () => {
    const menu = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

    if (menu.length > 0) {
        menu.forEach(el => {
            el.addEventListener('click', () => {
                const item = document.getElementById(el.dataset.target);

                el.classList.toggle('is-active');
                item.classList.toggle('is-active');
            });
        });
    }
});

/**
 * Máscara de telefone - Utilizada em um artigo do site (prática depreciada)
 */
let mascaraTelefone;
window.addEventListener("DOMContentLoaded", () => {
    mascaraTelefone = function (event) {
        let tecla = event.key;
        let telefone = event.target.value.replace(/\D+/g, "");

        if (/^[0-9]$/i.test(tecla)) {
            telefone = telefone + tecla;
            let tamanho = telefone.length;

            if (tamanho >= 12) {
                return false;
            }

            if (tamanho > 10) {
                telefone = telefone.replace(/^(\d\d)(\d{5})(\d{4}).*/, "($1) $2-$3");
            } else if (tamanho > 5) {
                telefone = telefone.replace(/^(\d\d)(\d{4})(\d{0,4}).*/, "($1) $2-$3");
            } else if (tamanho > 2) {
                telefone = telefone.replace(/^(\d\d)(\d{0,5})/, "($1) $2");
            } else {
                telefone = telefone.replace(/^(\d*)/, "($1");
            }

            event.target.value = telefone;
        }

        if (!["Backspace", "Delete"].includes(tecla)) {
            return false;
        }
    }
});