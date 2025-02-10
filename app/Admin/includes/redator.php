<div id="article-generator">
    <button @click="showModal = true" type="button" class="btn btn-primary">
        Gerar Artigo com IA
    </button>

    <div v-if="showModal" class="generator-modal" :style="modalStyle" @mousedown="startDrag">
        <div class="modal-header">
            <h3>Gerador de Artigos IA</h3>
            <button @click="closeModal" class="close">&times;</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label>Título/Tema:</label>
                <input v-model="inputs.topic" type="text" class="form-control">
            </div>

            <div class="form-group">
                <label>Instruções:</label>
                <ul class="instruction-list">
                    <li>Artigo técnico detalhado</li>
                    <li>Incluir exemplos práticos</li>
                    <li>Usar linguagem clara e objetiva</li>
                    <li>Adicionar dicas de otimização</li>
                </ul>
                <textarea v-model="inputs.instructions" class="form-control" rows="4"></textarea>
            </div>

            <div class="action-buttons">
                <button @click="generateArticle" class="btn btn-success" :disabled="loading">
                    <span v-if="loading">Gerando...</span>
                    <span v-else>Gerar Artigo</span>
                </button>
                <button @click="closeModal" class="btn btn-danger">Cancelar</button>
            </div>

            <div v-if="error" class="alert alert-danger mt-3">
                {{ error }}
            </div>
        </div>
    </div>
</div>

<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script>
    const {
        createApp
    } = Vue;

    createApp({
        data() {
            return {
                showModal: false,
                loading: false,
                error: null,
                inputs: {
                    topic: '',
                    instructions: ``
                },
                modalStyle: {
                    top: '50px',
                    left: '50px',
                    width: '600px'
                },
                isDragging: false,
                dragStartX: 0,
                dragStartY: 0
            }
        },
        methods: {
            async generateArticle() {
                this.loading = true;
                this.error = null;

                try {
                    const prompt =
                        `Instruções:
                        Gere um artigo completo com base nos seguintes parâmetros:
                        Tema/Assunto: ${this.inputs.topic}
                        Instruções Específicas: ${this.inputs.instructions}

                        Formato de Resposta Exigido (STRICT JSON) DEVOLVA APENAS ESSE JSON:
                        {
                            "titulo": "Título do artigo com foco em SEO",
                            "descricao": "Descrição otimizada para SEO (120-150 caracteres)",
                            "texto": "Conteúdo completo em HTML formatado",
                            "url": "url-amigavel-para-seo",
                            "tags": "tag1,tag2,tag3,tag4"
                        }

                        Regras:
                        - Gere um artigo completo com:
                            * Introdução contextualizada
                            * Exemplos práticos
                            * Conclusão com resumo
                            * Dicas de melhores práticas
                        - Mantenha estrutura HTML original com emojis e elementos de formatação
                        - Inclua pelo menos 3 subtítulos (h2/h3) no conteúdo
                        - Links internos devem ser mantidos com URLs originais
                        - Blocos de código devem ser formatados corretamente`;

                    await this.streamContent(prompt);

                } catch (err) {
                    this.error = 'Erro na geração do artigo: ' + err.message;
                } finally {
                    this.loading = false;
                }
            },

            async streamContent(prompt) {
                try {
                    const response = await fetch('/api/chat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({
                            prompt
                        })
                    });

                    const reader = response.body.getReader();
                    const decoder = new TextDecoder();
                    let accumulatedData = '';

                    while (true) {
                        const {
                            done,
                            value
                        } = await reader.read();
                        if (done) break;

                        accumulatedData += decoder.decode(value, {
                            stream: true
                        });

                        try {
                            const jsonData = JSON.parse(accumulatedData);
                            this.populateFields(jsonData);
                            break; 
                        } catch (e) {
                            continue;
                        }
                    }

                } catch (err) {
                    throw new Error(`Falha na geração: ${err.message}`);
                }
            },

            populateFields(data) {
                if (data.titulo) {
                    document.querySelector('[name="titulo"]').value = data.titulo;
                }

                if (data.descricao) {
                    document.querySelector('[name="descricao"]').value = data.descricao;
                }

                if (data.texto) {
                    tinymce.editors[0].setContent(data.texto);
                }

                if (data.url) {
                    document.querySelector('[name="url"]').value = data.url;
                }

                if (data.tags) {
                    document.querySelector('[name="tags"]').value = data.tags;
                }

                ['titulo', 'descricao', 'url', 'tags'].forEach(field => {
                    const element = document.querySelector(`[name="${field}"]`);
                    if (element) {
                        element.dispatchEvent(new Event('input'));
                    }
                });
            },

            startDrag(e) {
                this.isDragging = true;
                this.dragStartX = e.clientX - parseInt(this.modalStyle.left);
                this.dragStartY = e.clientY - parseInt(this.modalStyle.top);

                document.addEventListener('mousemove', this.handleDrag);
                document.addEventListener('mouseup', this.stopDrag);
            },

            handleDrag(e) {
                if (!this.isDragging) return;

                this.modalStyle.left = `${e.clientX - this.dragStartX}px`;
                this.modalStyle.top = `${e.clientY - this.dragStartY}px`;
            },

            stopDrag() {
                this.isDragging = false;
                document.removeEventListener('mousemove', this.handleDrag);
                document.removeEventListener('mouseup', this.stopDrag);
            },

            closeModal() {
                this.showModal = false;
                this.resetState();
            },

            resetState() {
                this.inputs.instructions = `Gere um artigo completo com:\n- Introdução contextualizada\n- Exemplos práticos\n- Conclusão com resumo\n- Dicas de melhores práticas`;
                this.loading = false;
                this.error = null;
            }
        }
    }).mount('#article-generator');
</script>

<style>
    .generator-modal {
        position: fixed;
        z-index: 1000;
        background: white;
        border: 1px solid #ccc;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        cursor: move;
    }

    .modal-header {
        padding: 15px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-header .close {
        border: none;
        background: none;
        font-size: 24px;
        cursor: pointer;
    }

    .modal-body {
        padding: 15px;
        max-height: 80vh;
        overflow-y: auto;
    }

    .instruction-list {
        list-style-type: disc;
        padding-left: 20px;
        color: #666;
        margin: 10px 0;
    }

    .action-buttons {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }
</style>