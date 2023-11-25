<script>
    function padronizarTextoTMEditor() {
        const tinymceIframe = document.querySelector('iframe[id^="texto"]');

        if (tinymceIframe) {
            const tinymceContent = tinymceIframe.contentDocument || tinymceIframe.contentWindow.document;

            tinymceContent.getElementById('tinymce').dispatchEvent(new Event('click'));

            setTimeout(selecionarTodoTexto, 200);
        }
    }

    function selecionarTodoTexto() {
        const tinymceContent = getTinyMCEContent();
        tinymceContent.execCommand('selectAll');

        setTimeout(selecionarFontFamily, 200);
    }

    function selecionarFontFamily() {
        document.querySelector('[aria-label="Font Family"]').click();

        setTimeout(aplicarFontLora, 200);
    }

    function aplicarFontLora() {
        document.querySelector('[style="font-family:lora,serif"]').click();

        setTimeout(selecionarFontSize, 200);
    }

    function selecionarFontSize() {
        document.querySelector('[aria-label="Font Sizes"]').click();

        setTimeout(aplicarFontSize15pt, 200);
    }

    function aplicarFontSize15pt() {
        getElementByInnerText('15pt').click();

        setTimeout(selecionarFormat, 200);
    }

    function selecionarFormat() {
        getElementByInnerText('Format').click();

        setTimeout(selecionarBlocks, 200);
    }

    function selecionarBlocks() {
        getElementByInnerText('Blocks').click();

        setTimeout(selecionarParagraph, 200);
    }


    function selecionarParagraph() {
        getElementByInnerText('Paragraph', 'mce-text').click();
    }

    function getElementByInnerText(innerText, classe = false) {
        return Array.from(document.querySelectorAll(classe ? `div span.${classe}` : 'div span'))
            .find(span => span.innerHTML.trim() === innerText);
    }

    function getTinyMCEContent() {
        const tinymceIframe = document.querySelector('iframe[id^="texto"]');
        return tinymceIframe.contentDocument || tinymceIframe.contentWindow.document;
    }
</script>
