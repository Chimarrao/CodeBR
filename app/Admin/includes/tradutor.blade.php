<script>
    const button = document.querySelector('.chat-btn');
    button.addEventListener('click', function() {
        const idArtigo = button.getAttribute('data-id');
        const texto = button.getAttribute('data-texto');
        const titulo = button.getAttribute('data-titulo');
        const descricao = button.getAttribute('data-descricao');
        const url = button.getAttribute('data-url');
        const tags = button.getAttribute('data-tags');
        const lang = button.getAttribute('data-lang');

        const prompt = `Instruções
            Você irá traduzir este artigo abaixo, para o idioma: inglês
            Mantenha exatamente a mesma estrutura, emojis, imagens e tudo mais... apenas troque o texto de idioma
            Se o artigo tiver bloco de código e o código estiver em pt-br, adapte ele para inglês, mas certifique-se que ele irá rodar... (devem ser adaptadas funções, variáveis e comentários, mas mantendo exatamente o mesmo funcionamento)
            ATENCAO: Você me devolverá apenas um JSON no formato abaixo: 
                JSON de exemplo: 
                    {
                    "titulo": "Como Otimizar e Utilizar a Função substr no PHP",
                    "descricao": "Aprenda a usar a função substr no PHP para manipular strings de forma eficiente, com exemplos práticos e dicas de otimização.",
                    "texto": "<p><strong><span style=\"font-family: lora, serif; font-size: 20pt;\">Como Otimizar e Utilizar a Função substr no PHP</span></strong></p><p><img src=\"https://cdn.statically.io/gh/Chimarrao/CodeBR-img/img/images/internas/como-otimizar-e-utilizar-a-fun-o-substr-no-php-par-30257141.webp\" width=\"1280\" height=\"720\" alt=\"......"
                    "url": "aprenda-a-funcao-substr-php"
                    "tags": "substr,php,funcao"
                    }
            Titulo do artigo: ${titulo}
            Descrição do artigo: ${descricao}
            URL do artigo: ${url}
            Tags do artigo: ${tags}
            Texto do artigo: ${texto}`;

        // Modal de loading inicial
        Swal.fire({
            title: 'Traduzindo... <img src="https://kapowaz.github.io/square-flags/flags/us.svg" width="20">',
            text: 'Por favor, aguarde enquanto traduzimos o artigo para o inglês.',
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading()
        });

        fetch('/api/chat', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    prompt: prompt
                })
            })
            .then(response => {
                const reader = response.body.getReader();
                const decoder = new TextDecoder();
                let accumulatedData = '';
                let streamAlert = null;

                setTimeout(() => {
                    Swal.close();

                    streamAlert = Swal.fire({
                        title: 'Traduzindo... <img src="https://kapowaz.github.io/square-flags/flags/us.svg" width="20">',
                        html: `<pre id="stream-output" class="pre-editor"></pre>`,
                        showConfirmButton: true,
                        confirmButtonText: 'Salvar',
                        showCancelButton: true,
                        cancelButtonText: 'Fechar',
                        allowOutsideClick: false,
                        didOpen: () => {
                            document.getElementById('stream-output').textContent = accumulatedData;
                        },
                        preConfirm: () => {
                            fetch('/api/inserir-artigo-traduzido', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                                    },
                                    body: JSON.stringify({
                                        json: accumulatedData,
                                        id_artigo: idArtigo,
                                        lang: lang
                                    })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data)
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Sucesso!',
                                        text: 'Artigo traduzido salvo com sucesso!',
                                        confirmButtonText: 'OK'
                                    });
                                });
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire('Salvo!', 'O conteúdo foi salvo com sucesso.', 'success');
                        } else if (result.dismiss === Swal.DismissReason.cancel) {
                            Swal.fire('Cancelado', 'O conteúdo não foi salvo.', 'error');
                        }
                    });
                }, 300);

                function readStream() {
                    reader.read().then(({
                        done,
                        value
                    }) => {
                        const chunk = decoder.decode(value, {
                            stream: true
                        });
                        console.log('Chunk recebido (raw):', chunk);
                        accumulatedData += chunk;

                        const outputElement = document.getElementById('stream-output');
                        if (outputElement) {
                            outputElement.textContent = accumulatedData;
                            outputElement.scrollTop = outputElement.scrollHeight;
                        }

                        if (done) {
                            return;
                        }

                        readStream();
                    }).catch(error => {
                        console.error(error)
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro no Stream',
                            text: 'Ocorreu um erro durante o processo de tradução!'
                        });
                    });
                }

                readStream();
            })
            .catch(error => {
                console.error(error)
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Falha ao iniciar o processo de tradução!'
                });
            });
    });
</script>


<style>
    .swal2-popup.swal2-modal.swal2-show {
        width: 170rem;
    }

    .pre-editor {
        background: #1e1e1e;
        color: #d4d4d4;
        padding: 20px;
        border-radius: 5px;
        text-align: left;
        height: 60rem;
        width: 167rem;
        overflow-y: auto;
        font-family: 'Courier New', monospace;
        white-space: pre-wrap;
    }
</style>
