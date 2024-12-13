<template>
    <Menu @pesquisar="pesquisar" />
    <Cabecalho />

    <div class="upload-arquivo">
        <div class="field">
            <div class="file is-boxed is-centered">
                <label class="file-label">
                    <input class="file-input" type="file" @change="handleFileChange" ref="fileInput">
                    <span class="file-cta">
                        <span class="file-icon">
                            <i class="fa fa-upload"></i>
                        </span>
                        <span class="file-label">
                            Selecione um arquivo…
                        </span>
                    </span>
                </label>
            </div>
        </div>

        <div v-if="uploading" class="field">
            <progress class="progress is-primary" :value="uploadProgress" max="100">{{ uploadProgress }}%</progress>
            <p>Enviando arquivo... {{ uploadProgress }}%</p>
        </div>

        <div class="field">
            <button class="button is-primary" @click="uploadFile" :disabled="!selectedFile || uploading">
                {{ uploading ? 'Enviando...' : 'Enviar' }}
            </button>
        </div>

        <div v-if="uploadedLink" class="notification is-success">
            <p><strong>Upload concluído!</strong></p>
            <p>Link: <a :href="uploadedLink" target="_blank">{{ uploadedLink }}</a></p>
            <p>Tamanho: {{ fileSizeFormatted }}</p>
        </div>

        <div v-if="errorMessage" class="notification is-danger">
            {{ errorMessage }}
        </div>
    </div>

    <Rodape />
</template>

<script>
import Menu from './../Menu.vue';
import Rodape from './../Rodape.vue';
import Cabecalho from './../Cabecalho.vue';

export default {
    components: {
        Menu,
        Rodape,
        Cabecalho,
    },
    data() {
        return {
            selectedFile: null,
            uploading: false,
            uploadProgress: 0,
            uploadedLink: '',
            errorMessage: '',
            fileSize: 0,
            fileName: ''
        };
    },
    computed: {
        fileSizeFormatted() {
            if(!this.fileSize) return '';
            const kb = this.fileSize / 1024;
            if(kb < 1024) {
                return kb.toFixed(2) + ' KB';
            } else {
                return (kb / 1024).toFixed(2) + ' MB';
            }
        }
    },
    methods: {
        handleFileChange(e) {
            const files = e.target.files;
            if (files && files.length > 0) {
                this.selectedFile = files[0];
                this.fileName = files[0].name;
                this.fileSize = files[0].size;
                this.uploadedLink = '';
                this.errorMessage = '';
            }
        },
        async uploadFile() {
            if (!this.selectedFile) {
                this.errorMessage = 'Nenhum arquivo selecionado.';
                return;
            }

            this.uploading = true;
            this.uploadProgress = 0;
            this.errorMessage = '';
            this.uploadedLink = '';

            try {
                let formData = new FormData();
                formData.append('file', this.selectedFile);

                const response = await fetch('https://anonymfile.com/api/v1/upload', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.status && result.data && result.data.file.url.full) {
                    const link = result.data.file.url.full;
                    this.uploadedLink = link;

                    const salvaResponse = await fetch('/api/arquivos', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify({
                            nome: this.fileName,
                            link: link,
                            tamanho: this.fileSize
                        })
                    });
                    const salvaResult = await salvaResponse.json();

                    if (!salvaResult.success) {
                        this.errorMessage = salvaResult.message || 'Erro ao salvar no banco de dados.';
                    }
                } else {
                    this.errorMessage = 'Erro ao enviar arquivo para o anonymfile.';
                }

            } catch (err) {
                console.error(err);
                this.errorMessage = 'Ocorreu um erro inesperado no upload.';
            } finally {
                this.uploading = false;
                this.uploadProgress = 100;
            }
        }
    }
};
</script>

<style scoped>
.upload-arquivo {
    margin-top: 2rem;
}
</style>
