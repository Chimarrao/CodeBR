<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.all.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.15.10/dist/sweetalert2.min.css" rel="stylesheet">

<style>
    .swal2-title {
        font-size: 24px !important;
    }

    .swal2-content {
        font-size: 18px !important;
    }

    .swal2-confirm {
        font-size: 16px !important;
    }
</style>

<script>
    const button = document.querySelector('.chat-btn');
    button.addEventListener('click', function() {
        const idArtigo = button.getAttribute('data-id');
        const texto = button.getAttribute('data-texto');
        const titulo = button.getAttribute('data-titulo');
        const descricao = button.getAttribute('data-descricao');
        const url = button.getAttribute('data-url');
        const tags = button.getAttribute('data-tags');

        const prompt = `
                Instruções
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
                Titulo do artigo: ` + titulo +
            `Descrição do artigo: ` + descricao +
            `URL do artigo: ` + url +
            `Tags do artigo: ` + tags +
            `Texto do artigo: ` + texto;

        // Exibe o modal de loading
        Swal.fire({
            title: 'Traduzindo... <img src="https://kapowaz.github.io/square-flags/flags/us.svg" width="20">',
            text: 'Por favor, aguarde enquanto traduzimos o artigo para o inglês.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
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
            .then(response => response.json())
            .then(data => {
                const retorno = data.response;

                // Fecha o modal de loading
                Swal.close();

                // Exibe o modal de sucesso
                Swal.fire({
                    icon: 'success',
                    title: 'Tradução Concluída! <img src="https://kapowaz.github.io/square-flags/flags/us.svg" width="20">',
                    text: 'O artigo foi traduzido para o inglês com sucesso.',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                });

                // Envia o JSON traduzido para a API de inserção
                fetch('/api/inserir-artigo-traduzido', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            json: retorno,
                            id_artigo: idArtigo
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        console.log(data.response);
                    })
                    .catch(error => {
                        console.error('Erro ao inserir o artigo traduzido:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Ocorreu um erro ao salvar o artigo traduzido.',
                            confirmButtonText: 'OK',
                            allowOutsideClick: false,
                        });
                    });
            })
            .catch(error => {
                console.error('Erro ao traduzir o artigo:', error);

                // Fecha o modal de loading
                Swal.close();

                // Exibe o modal de erro
                Swal.fire({
                    icon: 'error',
                    title: 'Erro',
                    text: 'Ocorreu um erro ao traduzir o artigo.',
                    confirmButtonText: 'OK',
                    allowOutsideClick: false,
                });
            });
    });
</script>
