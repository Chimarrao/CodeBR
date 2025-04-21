<script src="https://cdn.jsdelivr.net/npm/recordrtc@5.4.6/RecordRTC.min.js"></script>
<script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.12/ace.js"></script>

<div id="article-generator">
    <button @click="showModal = true" type="button" class="btn btn-primary">
        Redator com IA
    </button>

    <div v-if="showModal" class="generator-modal" :style="modalStyle" @mousedown="startDrag">
        <div class="modal-header">
            <h3>Gerador de Artigos IA</h3>
            <button @click="closeModal" class="close">&times;</button>
        </div>

        <div class="modal-body">
            <div class="form-group">
                <label>Título/Tema:</label>
                <input v-model="inputs.titulo" type="text" class="form-control" placeholder="Digite o título do artigo">
            </div>

            <div class="form-group">
                <label>URL Base (Inspiração):</label>
                <input v-model="inputs.urlBase" type="text" class="form-control" placeholder="Cole a URL de referência">
            </div>

            <div class="form-group">
                <label>Quantidade de palavras:</label>
                <input v-model="inputs.qtdPalavras" type="text" class="form-control" placeholder="Digite o título do artigo">
            </div>

            <div id="audio-recorder">
                <button v-if="!transcrevendo" type="button" @click="startRecording" :disabled="isRecording" class="btn btn-primary">
                    🎤 Iniciar Gravação
                </button>
                <button v-if="!transcrevendo" type="button" @click="pararGravacao" :disabled="!isRecording" class="btn btn-danger">
                    ⏹ Parar Gravação
                </button>

                <div v-if="transcrevendo" class="btn btn-light">
                    🔄 Transcrevendo
                </div>

                <button v-if="!transcrevendo && audioUrl" type="button" @click="downloadAudio" class="btn btn-success mt-2">⬇️ Baixar Áudio</button>

                <div v-if="!transcrevendo && audioUrl" class="mt-3">
                    <audio controls :src="audioUrl"></audio>
                </div>
            </div>

            <!-- Instruções -->
            <div class="form-group">
                <textarea v-model="inputs.instructions" class="form-control" rows="4" placeholder="Instruções específicas para a IA"></textarea>
            </div>

            <!-- Botões de Ação -->
            <div class="action-buttons">
                <button @click="generateArticle" class="btn btn-success" :disabled="loading">
                    <span v-if="loading">Gerando...</span>
                    <span v-else>Gerar Artigo</span>
                </button>
                <button @click="closeModal" class="btn btn-danger">Cancelar</button>
            </div>

            <!-- Mensagem de Erro -->
            <div v-if="error" class="alert alert-danger mt-3">
                {{ error }}
            </div>

            <div id="ace-editor" style="height: 300px; width: 100%; margin-top: 10px;"></div>
        </div>
    </div>
</div>

<script>
    const {
        createApp
    } = Vue;

    createApp({
        data() {
            return {
                isRecording: false,
                showModal: false,
                loading: false,
                error: null,
                inputs: {
                    urlBase: '',
                    audioFile: null,
                    instructions: ``,
                    qtdPalavras: 1000
                },
                modalStyle: {
                    top: '50px',
                    left: '50px',
                    width: '80rem',
                    padding: '1rem',
                },
                isDragging: false,
                dragStartX: 0,
                dragStartY: 0,
                recorder: null,
                audioBlob: null,
                audioUrl: null,
                aceEditor: null,
                transcrevendo: false
            };
        },
        mounted() {
        },
        methods: {
            setInstrucoes(conteudoAudio = '') {
                this.inputs.instructions = this.inputs.instructions + '\\n' + conteudoAudio;
            },
            initAceEditor() {
                if (!this.aceEditor) {
                    this.aceEditor = ace.edit('ace-editor', {
                        mode: 'ace/mode/text',
                        theme: 'ace/theme/dracula',
                        fontSize: 14,
                        showPrintMargin: false,
                        wrap: true,
                        enableBasicAutocompletion: true,
                        enableLiveAutocompletion: true,
                    });

                    this.aceEditor.session.setUseWorker(false);
                }
            },
            async generateArticle() {
                this.initAceEditor();
                this.loading = true;
                this.error = null;

                try {
                    let prompt =
                        `Instruções:
                            Gere um artigo com base nos seguintes parâmetros:
                            Título: ${this.inputs.titulo}
                            URL Base (Inspiração): ${this.inputs.urlBase}
                            Instruções (áudio transcrito): ${this.inputs.instructions}
                            Se precisar coloque alguns emojis no título e descrição

                            Formato de Resposta Exigido (STRICT JSON) DEVOLVA APENAS ESSE JSON:
                            {
                                "titulo": "Título do artigo com foco em SEO",
                                "descricao": "Descrição otimizada para SEO (120-150 caracteres)",
                                "texto": "",
                                "url": "url-amigavel-para-seo",
                                "tags": "tag1,tag2,tag3,tag4"
                            }
                            `;

                    await this.streamContent(prompt);

                    prompt = `
                    Gere um artigo com base nos seguintes parâmetros:
                            Título: ${this.inputs.titulo}
                            URL Base (Inspiração): ${this.inputs.urlBase}
                            Quantidade de palavras (sem contar as tags HTML, apenas texto bruto): ${this.inputs.qtdPalavras}
                            Instruções (áudio transcrito): ${this.inputs.instructions}
                            
                            Adicione emojis no texto

                             Formato de Resposta Exigido (STRICT JSON) DEVOLVA APENAS ESSE JSON:
                            {
                                "texto": "AQUI O TEXTO",
                            }
                            
                            Regras:
                            - Blocos de código devem ser formatados corretamente
                            - Descrição deve ter entre 120 e 200 caracteres
                            !!!! ATENÇÃO: FOCO TOTAL EM SEO !!!
                            - O HTML deve ter este formato (exemplo + emojis):
                                    <p>
                                        <strong>
                                            <span style="font-family: lora, serif; font-size: 20pt;">How to Optimize and Use the substr Function in PHP to Efficiently Manipulate Strings</span>
                                        </strong>
                                    </p>
                                    <p>
                                        <img src="https://www.shutterstock.com/image-photo/on-white-background-buttons-used-260nw-2048122016.jpg" />
                                    </p>


                                    <p style="text-align: justify;">
                                        <span style="font-family: lora, serif; font-size: 15pt;">The <strong>substr</strong> function in PHP is one of the most powerful tools for string manipulation. It allows you to extract specific parts of a string, simplifying tasks such as text formatting, truncation, and dynamic content adjustment. In this article, we will explore the efficient use of the <strong>PHP substr</strong> function, including practical examples and optimization tips.
                                        </span>
                                    </p>

                                    <pre><code class="language-php">
                                    // Basic example of PHP substr
                                    $string = "Learn PHP with substr!";
                                    $part = substr($string, 8, 3);

                                    echo $part; // Output: PHP
                                    </code></pre>`;

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

                    this.aceEditor.setValue('');

                    while (true) {
                        const {
                            done,
                            value
                        } = await reader.read();
                        if (done) break;

                        accumulatedData += decoder.decode(value, {
                            stream: true
                        });

                        this.aceEditor.setValue(accumulatedData);
                    }

                    try {
                        accumulatedData = accumulatedData.replace(/```(json)?\s*([\s\S]*?)\s*```/g, '$2');
                        accumulatedData = accumulatedData.replace(/```(html)?\s*([\s\S]*?)\s*```/g, '$2');
                        const jsonData = JSON.parse(accumulatedData);
                        this.populateFields(jsonData);
                    } catch (e) {
                        console.log(e)
                    }

                } catch (err) {
                    throw new Error(`Falha na geração: ${err.message}`);
                }
            },

            populateFields(data) {
                if (data.titulo) {
                    document.querySelector('[name="artigo"]').value = data.titulo;
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

            handleAudioUpload(event) {
                const file = event.target.files[0];
                if (file) {
                    this.inputs.audioFile = file;
                    console.log('Áudio carregado:', file.name);
                }
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
            },

            async startRecording() {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({
                        audio: true
                    });

                    this.recorder = RecordRTC(stream, {
                        type: 'audio',
                        mimeType: 'audio/webm',
                        recorderType: RecordRTC.StereoAudioRecorder,
                        timeSlice: 1000,
                        desiredSampRate: 16000,
                        ondataavailable: (blob) => {
                            this.audioBlob = blob;
                        },
                    });

                    this.recorder.startRecording();
                    this.isRecording = true;
                    this.audioUrl = null;
                } catch (error) {
                    console.error('Erro ao acessar o microfone:', error);
                    alert('Erro ao acessar o microfone. Verifique as permissões.');
                }
            },

            async pararGravacao() {
                await new Promise((resolve) => {
                    const stopCallback = () => {
                        resolve();
                    };

                    this.recorder.stopRecording(stopCallback);
                });

                this.isRecording = false;
                this.audioBlob = this.recorder.getBlob();
                if (this.audioBlob instanceof Blob) {
                    this.audioUrl = URL.createObjectURL(this.audioBlob);
                } else {
                    console.error('Blob de áudio inválido:', this.audioBlob);
                    this.audioUrl = null;
                }
                if (this.recorder.stream) {
                    this.recorder.stream.getTracks().forEach(track => track.stop());
                }

                await this.converterAudioParaTexto();
            },

            async converterAudioParaTexto() {
                this.transcrevendo = true;
                const audioFile = this.audioBlob
                const apiKey = '';

                try {
                    const response = await fetch('https://api.deepgram.com/v1/listen?language=pt-BR&model=nova-2', {
                        method: 'POST',
                        headers: {
                            Authorization: `Token ${apiKey}`,
                        },
                        body: audioFile,
                    });

                    if (!response.ok) {
                        const errorData = await response.json();
                        throw new Error(errorData.error || 'Erro ao transcrever áudio.');
                    }

                    const data = await response.json();
                    const transcript = data.results.channels[0].alternatives[0].transcript;

                    console.log(`Texto transcrito:\n${transcript}`);
                    this.setInstrucoes(transcript);
                } catch (error) {
                    console.error('Erro ao transcrever áudio:', error.message);
                    console.log(`Erro: ${error.message}`);
                }

                this.transcrevendo = false;
            },

            downloadAudio() {
                if (this.audioBlob) {
                    const link = document.createElement('a');
                    link.href = this.audioUrl;
                    link.download = `gravacao-${Date.now()}.webm`;
                    link.click();
                }
            },
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

    #audio-recorder {
        margin: 20px;
    }

    button {
        margin-right: 10px;
    }

    audio {
        margin-top: 10px;
    }

    #ace-editor {
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