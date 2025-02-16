<div id="translator-app">
    <!-- Seus botões existentes aqui -->
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const {
        createApp
    } = Vue;
    createApp({
        data() {
            return {
                editor: null,
                currentStep: 0,
                translationData: {},
                popupEditor: null,
            };
        },
        mounted() {
            document.querySelectorAll('.chat-btn').forEach(button => {
                button.addEventListener('click', (e) => {
                    this.handleTranslation(e.currentTarget);
                });
            });
        },
        methods: {
            handleTranslation(button) {
                const data = {
                    id: button.getAttribute('data-id'),
                    titulo: button.getAttribute('data-titulo'),
                    descricao: button.getAttribute('data-descricao'),
                    url: button.getAttribute('data-url'),
                    tags: button.getAttribute('data-tags'),
                    lang: button.getAttribute('data-lang'),
                    texto: button.getAttribute('data-texto'),
                };
                this.translationData = data;
                this.currentStep = 0;
                this.translateNextPart();
            },
            async translateNextPart() {
                const steps = [
                    this.translateTitle,
                    this.translateDescription,
                    this.translateUrl,
                    this.translateTags,
                    this.translateTextParts,
                ];

                if (this.currentStep < steps.length) {
                    await steps[this.currentStep]();
                    this.currentStep++;

                    setTimeout(() => {
                        this.translateNextPart();
                    }, 2000);
                } else {
                    this.finalizeTranslation();
                }
            },
            async translateTitle() {
                const prompt = `ME DEVOLVA APENAS A ADAPTACAO DO IDIOMA: Traduza o seguinte título para ${this.translationData.lang}: ${this.translationData.titulo}`;

                this.openPopup("Título", this.translationData.titulo, "titulo");

                // Atualiza conforme os dados chegam
                const translatedTitle = await this.fetchTranslation(prompt, (partialResult) => {
                    this.updatePopupInputValue("titulo", partialResult);
                });

                this.translationData.titulo = translatedTitle.trim();
                this.updatePopupInputValue("titulo", this.translationData.titulo);
            },
            async translateDescription() {
                const prompt = `ME DEVOLVA APENAS A ADAPTACAO DO IDIOMA: Traduza a seguinte descrição para ${this.translationData.lang}: ${this.translationData.descricao}`;

                this.openPopup("Descrição", this.translationData.descricao, "descricao");

                const translatedDescription = await this.fetchTranslation(prompt, (partialResult) => {
                    this.updatePopupInputValue("descricao", partialResult);
                });

                this.translationData.descricao = translatedDescription.trim();
                this.updatePopupInputValue("descricao", this.translationData.descricao);
            },
            async translateUrl() {
                const prompt = `ME DEVOLVA APENAS A ADAPTACAO DO IDIOMA (SE NAO TIVER, DEVOLVA 'ERRO'): Adapte o slug (é a URL de um artigo que estamos traduzindo) do idioma original para ${this.translationData.lang}: ${this.translationData.url}`;

                this.openPopup("URL", this.translationData.url, "url");

                const translatedUrl = await this.fetchTranslation(prompt, (partialResult) => {
                    this.updatePopupInputValue("url", partialResult);
                });

                this.translationData.url = translatedUrl.trim().replace(/\s+/g, '-').toLowerCase();
                this.updatePopupInputValue("url", this.translationData.url);
            },
            async translateTags() {
                const prompt = `ME DEVOLVA APENAS A ADAPTACAO DO IDIOMA (SE NAO TIVER, DEVOLVA AS MESMAS TAGS DO IDIOMA ORIGINAL): Traduza as seguintes tags para ${this.translationData.lang}: ${this.translationData.tags}`;

                this.openPopup("Tags", this.translationData.tags, "tags");

                const translatedTags = await this.fetchTranslation(prompt, (partialResult) => {
                    this.updatePopupInputValue("tags", partialResult);
                });

                this.translationData.tags = translatedTags.trim().split(',').map(tag => tag.trim()).join(',');
                this.updatePopupInputValue("tags", this.translationData.tags);
            },
            async delay(time) {
                return new Promise(resolve => setTimeout(resolve, time));
            },
            async translateTextParts() {
                const parts = this.splitHtmlIntoParts(this.translationData.texto);
                const translatedParts = [];

                this.openPopup("Texto", '', "texto");
                this.initializeEditor();
                let isFirst = true;

                for (const part of parts) {
                    await this.delay(2000);
                    if (isFirst) {
                        isFirst = false;
                    }
                    const html = await this.translateHtmlContent(part, isFirst);
                    if (html) {
                        clean = html.replace(/```(html|php|js|ts|javascript|typescript|sql|python|rust)?\s*([\s\S]*?)\s*```/g, '$2');
                        clean = clean.replace(/width="(120|128)"/g, (match, num) => `width="${num}0"`);
                        translatedParts.push(clean);
                        this.translationData.texto = translatedParts.join('\n');
                        this.updateEditorWithTranslatedText(this.translationData.texto);
                    }
                }
            },
            initializeEditor() {
                if (!this.popupEditor) {
                    this.popupEditor = ace.edit('editor', {
                        mode: 'ace/mode/json',
                        theme: 'ace/theme/dracula',
                        fontSize: 16,
                        showPrintMargin: false,
                        wrap: true,
                        enableBasicAutocompletion: true,
                        enableLiveAutocompletion: true,
                    });
                    this.popupEditor.setOptions({
                        fontFamily: 'Fira Code, monospace',
                    });
                    this.popupEditor.session.setUseWorker(false);
                }
            },
            updateEditorWithTranslatedText(translatedText) {
                if (!this.popupEditor) {
                    this.initializeEditor();
                }
                this.popupEditor.setValue(translatedText, -1);
            },
            splitHtmlIntoParts(html) {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const parts = [];
                const nodes = Array.from(doc.body.childNodes);
                const chunkSize = 20;
                for (let i = 0; i < nodes.length; i += chunkSize) {
                    const chunk = nodes.slice(i, i + chunkSize);
                    const part = chunk.map(node => node.outerHTML || node.textContent).join('');
                    parts.push(part);
                }
                return parts;
            },
            async translateHtmlContent(htmlContent, primeiraParte) {
                const prompt = `ME DEVOLVA APENAS O HTML: Traduza o seguinte conteúdo para ${this.translationData.lang}, mantendo as tags intactas: ${htmlContent}`;

                const translatedContent = await this.fetchTranslation(prompt, (partialResult) => {
                    temp = [];
                    // if (!primeiraParte && this.translationData.texto) {
                    //     temp.push(this.translationData.texto);
                    // }
                    temp.push(partialResult);

                    this.updateEditorWithTranslatedText(temp.join('\n'));
                });
                return translatedContent.trim();
            },
            openPopup(title, content, type) {
                let popup = document.getElementById('translation-popup');
                if (!popup) {
                    popup = document.createElement('div');
                    popup.id = 'translation-popup';
                    popup.style.position = 'fixed';
                    popup.style.top = '50%';
                    popup.style.left = '50%';
                    popup.style.transform = 'translate(-50%, -50%)';
                    popup.style.width = '80vw';
                    popup.style.height = '80vh';
                    popup.style.backgroundColor = '#fff';
                    popup.style.zIndex = '1000';
                    popup.style.boxShadow = '0 4px 8px rgba(0, 0, 0, 0.2)';
                    popup.style.padding = '20px';
                    popup.style.overflow = 'auto';
                    document.body.appendChild(popup);

                    const closeBtn = document.createElement('button');
                    closeBtn.innerText = 'Fechar';
                    closeBtn.style.position = 'absolute';
                    closeBtn.style.top = '10px';
                    closeBtn.style.right = '10px';
                    closeBtn.onclick = () => popup.remove();
                    popup.appendChild(closeBtn);
                }

                const titleLabel = document.createElement('h3');
                titleLabel.innerText = title;
                popup.appendChild(titleLabel);

                if (type === "texto") {
                    const editorElement = document.getElementById('editor');
                    if (!editorElement) {
                        const preElement = document.createElement('pre');
                        preElement.id = 'editor';
                        preElement.style.width = '100%';
                        preElement.style.height = '70vh';
                        popup.appendChild(preElement);
                    }
                } else {
                    const input = document.createElement('input');
                    input.type = 'text';
                    input.value = content;
                    input.style.width = '100%';
                    input.style.marginBottom = '10px';
                    input.id = `input-${type}`;
                    popup.appendChild(input);

                    input.oninput = () => {
                        this.translationData[type] = input.value;
                    };
                }
            },
            updatePopupInputValue(type, value) {
                const input = document.getElementById(`input-${type}`);
                if (input) {
                    input.value = value;
                }
            },
            finalizeTranslation() {
                Swal.fire({
                    title: 'Tradução finalizada!',
                    text: 'Todos os elementos foram traduzidos com sucesso.',
                    icon: 'success',
                }).then(() => {
                    this.saveTranslation(JSON.stringify(this.translationData));
                });
            },
            async fetchTranslation(prompt, updateCallback) {
                const response = await fetch('/api/chat', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        prompt
                    })
                });
                if (!response.ok) throw new Error('Erro na requisição');

                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let result = '';

                while (true) {
                    const {
                        done,
                        value
                    } = await reader.read();
                    if (done) break;
                    const chunk = decoder.decode(value, {
                        stream: true
                    });
                    result += chunk;
                    if (typeof updateCallback === 'function') {
                        updateCallback(result);
                    }
                }
                return result.trim();
            },
            async saveTranslation(data) {
                try {
                    await fetch('/api/inserir-artigo-traduzido', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            json: data,
                            id_artigo: this.translationData.id,
                            lang: this.translationData.lang
                        })
                    });
                    Swal.fire('Sucesso!', 'Artigo salvo com sucesso!', 'success');
                } catch (error) {
                    Swal.fire('Erro!', 'Falha ao salvar artigo!', 'error');
                }
            },
        }
    }).mount('#translator-app');
</script>

<style>
    .swal2-popup.swal2-modal.swal2-show {
        width: 100rem;
    }

    #editor {
        background: #1e1e1e;
        color: #d4d4d4;
        border-radius: 5px;
        font-family: 'Fira Code', monospace;
        font-size: 16px;
    }

    .ace_gutter {
        background: #1e1e1e !important;
        color: #858585 !important;
    }

    .ace_active-line {
        background: #2a2a2a !important;
    }

    .ace_cursor {
        border-left: 2px solid #d4d4d4 !important;
    }
</style>