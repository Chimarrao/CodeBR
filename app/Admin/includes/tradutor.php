<div id="translator-app">
    <!-- Os botões existentes já estarão aqui -->
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    const {
        createApp
    } = Vue;

    createApp({
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
                    Se o artigo tiver bloco de código e o código estiver em pt-br, adapte ele para  ` + data.lang + `, mas certifique-se que ele irá rodar... (devem ser adaptadas funções, variáveis e comentários, mas mantendo exatamente o mesmo funcionamento)
                    ATENCAO: Você me devolverá apenas um JSON no formato abaixo: 
                        JSON de exemplo: 
                            {
                            "titulo": "Como Otimizar e Utilizar a Função substr no PHP",
                            "descricao": "Aprenda a usar a função substr no PHP para manipular strings de forma eficiente, com exemplos práticos e dicas de otimização.",
                            "texto": "<p><strong><span style=\"font-family: lora, serif; font-size: 20pt;\">Como Otimizar e Utilizar a Função substr no PHP</span></strong></p><p><img src=\"https://cdn.statically.io/gh/Chimarrao/CodeBR-img/img/images/internas/como-otimizar-e-utilizar-a-fun-o-substr-no-php-par-30257141.webp\" width=\"1280\" height=\"720\" alt=\"......"
                            "url": "aprenda-a-funcao-substr-php"
                            "tags": "substr,php,funcao"
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
                window.accumulatedData =  '';

                setTimeout(() => {
                    Swal.close();
                    this.showStreamAlert(accumulatedData, data);
                }, 300);

                await this.readStream(reader, accumulatedData, data);
            },

            async readStream(reader, accumulatedData, data) {
                const decoder = new TextDecoder();

                while (true) {
                    const {
                        done,
                        value
                    } = await reader.read();
                    if (done) break;

                    const chunk = decoder.decode(value, {
                        stream: true
                    });
                    console.log(chunk)
                    accumulatedData += chunk;
                    window.accumulatedData += chunk;
                    this.updateStreamOutput(accumulatedData);
                }

                return accumulatedData;
            },

            showStreamAlert(initialData, data) {
                Swal.fire({
                    title: `Traduzindo... ${this.getFlag(data.lang)}`,
                    html: `<pre id="stream-output" class="pre-editor">${initialData}</pre>`,
                    showConfirmButton: true,
                    confirmButtonText: 'Salvar',
                    cancelButtonText: 'Fechar',
                    showCancelButton: true,
                    didOpen: () => this.scrollOutput(),
                    preConfirm: () => this.saveTranslation(window.accumulatedData, data)
                }).then(result => {
                    if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire('Cancelado', 'Tradução não salva', 'info');
                    }
                });
            },

            async saveTranslation(data, originalData) {
                console.log( data,
                            originalData.id,
                            originalData.lang)
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

            updateStreamOutput(data) {
                const output = document.getElementById('stream-output');
                if (output) {
                    output.textContent = data;
                    this.scrollOutput();
                }
            },

            scrollOutput() {
                const output = document.getElementById('stream-output');
                if (output) output.scrollTop = output.scrollHeight;
            }
        }
    }).mount('#translator-app');
</script>

<style>
    .swal2-popup.swal2-modal.swal2-show {
        width: 100rem;
    }

    .pre-editor {
        background: #1e1e1e;
        color: #d4d4d4;
        padding: 20px;
        border-radius: 5px;
        text-align: left;
        height: 50rem;
        width: 97rem;
        overflow-y: auto;
        font-family: 'Courier New', monospace;
        white-space: pre-wrap;
    }
</style>