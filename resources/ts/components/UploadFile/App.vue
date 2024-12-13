<template>
    <Menu @pesquisar="pesquisar" />
    <Cabecalho />

    <section class="section artigo" style="min-height: 80vh;">
        <div class="container has-text-white" style="max-width: 600px; margin: 0 auto;">
            
            <h1 class="title has-text-white has-text-centered">Anonymous File Upload</h1>
            <p class="has-text-centered">Maximum upload file size: 5000MB.</p>

            <div 
                class="box has-background-dark has-text-white"
                style="border: 2px dashed #aaa; padding: 2em; display: flex; flex-direction: column; align-items: center; justify-content: center; height: 300px;"
                @dragover.prevent
                @drop.prevent="handleDrop"
            >
                <div class="has-text-centered" style="margin-bottom: 1em;">
                    <p class="is-size-5">Drag & Drop your files or</p>
                </div>
                
                <div class="field has-addons has-text-centered">
                    <p class="control">
                        <label class="button is-link">
                            <input class="file-input" type="file" @change="handleFileChange" ref="fileInput" style="display: none;">
                            <span class="file-label">
                                Browse
                            </span>
                        </label>
                    </p>
                </div>
                
                <p v-if="selectedFile" class="has-text-centered" style="margin-top: 1em;">
                    Selected file: <strong>{{ fileName }}</strong> ({{ fileSizeFormatted }})
                </p>
            </div>

            <div v-if="uploading" class="field" style="margin-top: 2em;">
                <progress class="progress is-primary is-large" :value="uploadProgress" max="100">{{ uploadProgress }}%</progress>
                <p class="has-text-centered">Uploading... {{ uploadProgress }}% - {{ uploadSpeedFormatted }}</p>
            </div>

            <div class="field has-text-centered" style="margin-top: 2em;">
                <button class="button is-primary" @click="uploadFile" :disabled="!selectedFile || uploading">
                    {{ uploading ? 'Uploading...' : 'Upload' }}
                </button>
            </div>

            <div v-if="uploadedLink" class="notification is-success" style="margin-top: 2em;">
                <p><strong>Upload completed!</strong></p>
                <p>Link: <a :href="uploadedLink" target="_blank">{{ uploadedLink }}</a></p>
                <p>Size: {{ fileSizeFormatted }}</p>
            </div>

            <div v-if="errorMessage" class="notification is-danger" style="margin-top: 2em;">
                {{ errorMessage }}
            </div>
        </div>
    </section>

    <Rodape />
</template>

<script>
import Menu from './../Menu.vue';
import Rodape from './../Rodape.vue';
import Cabecalho from './../Cabecalho.vue';
import axios from 'axios';

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
            fileName: '',
            startTime: null,
            uploadSpeed: 0
        };
    },
    computed: {
        fileSizeFormatted() {
            if (!this.fileSize) return '';
            const kb = this.fileSize / 1024;
            if (kb < 1024) {
                return kb.toFixed(2) + ' KB';
            } else {
                return (kb / 1024).toFixed(2) + ' MB';
            }
        },
        uploadSpeedFormatted() {
            if (!this.uploadSpeed) return '0 KB/s';
            if (this.uploadSpeed < 1024) {
                return this.uploadSpeed.toFixed(2) + ' KB/s';
            } else {
                return (this.uploadSpeed / 1024).toFixed(2) + ' MB/s';
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
        handleDrop(e) {
            const files = e.dataTransfer.files;
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
                this.errorMessage = 'No file selected.';
                return;
            }

            this.uploading = true;
            this.uploadProgress = 0;
            this.errorMessage = '';
            this.uploadedLink = '';
            this.startTime = Date.now();
            this.uploadSpeed = 0;

            const formData = new FormData();
            formData.append('file', this.selectedFile);

            try {
                const response = await axios.post('https://anonymfile.com/api/v1/upload', formData, {
                    onUploadProgress: (progressEvent) => {
                        if (progressEvent.total) {
                            const timeElapsed = (Date.now() - this.startTime) / 1000; // tempo em segundos
                            const loadedKB = progressEvent.loaded / 1024;
                            this.uploadSpeed = loadedKB / timeElapsed;

                            const progress = (progressEvent.loaded * 100) / progressEvent.total;
                            this.uploadProgress = parseFloat(progress.toFixed(2));
                        }
                    }
                });

                const result = response.data;
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
                        this.errorMessage = salvaResult.message || 'Error saving to database.';
                    }
                } else {
                    this.errorMessage = 'Error uploading file to anonymfile.';
                }

            } catch (err) {
                console.error(err);
                this.errorMessage = 'Unexpected error during upload.';
            } finally {
                this.uploading = false;
            }
        }
    }
};
</script>

<style scoped>
.progress.is-primary.is-large {
    height: 1.5rem;
    border-radius: 4px;
}
</style>
