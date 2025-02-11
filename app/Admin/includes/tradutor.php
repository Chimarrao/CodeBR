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
                editor: null
            }
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

                this.startTranslation(data);
            },

            async startTranslation(data) {
                try {
                    const prompt = this.createPrompt(data);
                    this.showLoadingAlert(data.lang);

                    const response = await this.fetchTranslation(prompt);
                    await this.handleStreamResponse(response, data);
                } catch (error) {
                    this.handleError(error);
                }
            },

            createPrompt(data) {
                return `Instruções
                    Você irá traduzir este artigo abaixo, para o idioma: ` + data.lang + `
                    Mantenha exatamente a mesma estrutura, emojis, imagens e tudo mais... apenas troque o texto de idioma (texto titulo descricao tags e URL)
                    Se o artigo tiver bloco de código e o código estiver em pt-br, adapte ele para  ` + data.lang + `, mas certifique-se que ele irá rodar... (devem ser adaptadas funções, variáveis e comentários, mas mantendo exatamente o mesmo funcionamento). Além disso mantenha a formatação dele
                    OBS: Mantenha as imagens exatamente iguais, exatamente o mesmo link e mesmos tamanhos. Mude apenas o ALT delas se tiver
                    ATENCAO: Você me devolverá APENAS um JSON no formato abaixo (o texto deve ser COMPLETO ! SEM OCULTAR NADA): 
                        JSON de exemplo: 
                            {
                            "titulo": "Como Otimizar e Utilizar a Função substr no PHP",
                            "descricao": "Aprenda a usar a função substr no PHP para manipular strings de forma eficiente, com exemplos práticos e dicas de otimização.",
                            "url": "aprenda-a-funcao-substr-php",
                            "tags": "substr,php,funcao",
                            "texto": "<p><strong><span style=\"font-family: lora, serif; font-size: 20pt;\">Como Otimizar e Utilizar a Função substr no PHP</span></strong></p><p><img src=\"https://cdn.statically.io/gh/Chimarrao/CodeBR-img/img/images/internas/como-otimizar-e-utilizar-a-fun-o-substr-no-php-par-30257141.webp\" width=\"1280\" height=\"720\" alt=\"......"
                            }
                    Titulo do artigo: ${data.titulo}
                    Descrição do artigo: ${data.descricao}
                    URL do artigo: ${data.url}
                    Tags do artigo: ${data.tags}
                    Texto do artigo: ${data.texto}`;
            },

            showLoadingAlert(lang) {
                Swal.fire({
                    title: `Traduzindo... ${this.getFlag(lang)}`,
                    text: 'Por favor, aguarde enquanto traduzimos o artigo.',
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
            },

            async fetchTranslation(prompt) {
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
                return response;
            },

            async handleStreamResponse(response, data) {
                const reader = response.body.getReader();
                let accumulatedData = '';
                window.accumulatedData = '';

                Swal.close();
                this.showStreamAlert(accumulatedData, data);
                this.initializeEditor();

                await this.readStream(reader, accumulatedData, data);
            },

            async readStream(reader, accumulatedData, data) {
                const decoder = new TextDecoder();

                while (true) {
                    const { done, value } = await reader.read();
                    if (done) break;

                    const chunk = decoder.decode(value, { stream: true });
                    console.log(chunk);
                    accumulatedData += chunk;
                    window.accumulatedData += chunk;
                    this.updateStreamOutput(window.accumulatedData);
                }

                return accumulatedData;
            },

            showStreamAlert(initialData, data) {
                Swal.fire({
                    title: `Tradução ${this.getFlag(data.lang)} - Edite antes de salvar`,
                    html: `<pre id="editor" style="width: 100%; height: 70vh;"></pre>`,
                    showConfirmButton: true,
                    confirmButtonText: 'Salvar',
                    cancelButtonText: 'Fechar',
                    showCancelButton: true,
                    width: '90%',
                    didOpen: () => {
                        this.initializeEditor();
                        this.updateStreamOutput(initialData);
                    },
                    preConfirm: () => {
                        const content = this.editor.getValue();
                        return this.saveTranslation(content, data);
                    }
                }).then(result => {
                    if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire('Cancelado', 'Tradução não salva', 'info');
                    }
                });
            },

            initializeEditor() {
                this.editor = ace.edit('editor', {
                    mode: 'ace/mode/json', // Modo JSON
                    theme: 'ace/theme/dracula', // Tema Dracula
                    fontSize: 16, // Tamanho da fonte
                    showPrintMargin: false, // Remove a margem de impressão
                    wrap: true, // Quebra de linha automática
                    enableBasicAutocompletion: true, // Autocompletar básico
                    enableLiveAutocompletion: true, // Autocompletar em tempo real
                });

                this.editor.setOptions({
                    fontFamily: 'Fira Code, monospace', // Fonte personalizada
                });

                this.editor.session.setUseWorker(false); // Desativa o worker para melhorar desempenho
            },

            updateStreamOutput(data) {
                if (this.editor) {
                    this.editor.setValue(data, -1); // -1 para posicionar o cursor no início
                }
            },

            async saveTranslation(data, originalData) {
                console.log(data, originalData.id, originalData.lang);
                try {
                    await fetch('/api/inserir-artigo-traduzido', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: JSON.stringify({
                            json: data,
                            id_artigo: originalData.id,
                            lang: originalData.lang
                        })
                    });
                    Swal.fire('Sucesso!', 'Artigo salvo com sucesso!', 'success');
                } catch (error) {
                    Swal.fire('Erro!', 'Falha ao salvar artigo!', 'error');
                }
            },

            handleError(error) {
                console.error(error);
                Swal.fire('Erro!', 'Ocorreu um erro durante a tradução!', 'error');
            },

            getFlag(lang) {
                const country = lang === 'en-us' ? 'us' : 'es';
                return `<img src="https://kapowaz.github.io/square-flags/flags/${country}.svg" width="20">`;
            },
        }
    }).mount('#translator-app');
</script>

<style>
    .swal2-popup.swal2-modal.swal2-show {
        width: 100rem;
    }

    /* Estilo do Ace Editor */
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