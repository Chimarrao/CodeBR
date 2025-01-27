<template>
    <section class="hero is-fullheight is-light">
        <div class="hero-body">
            <div class="container">
                <h1 class="title has-text-centered">Online Image Converter</h1>
                <p class="subtitle has-text-centered">Drag and drop your files or click to upload.</p>
                <div class="box has-text-centered is-droppable" @dragover.prevent @drop.prevent="handleDrop">
                    <div>
                        <p class="has-text-weight-bold mb-4">Drag files here</p>
                        <input type="file" multiple accept="image/*" class="file-input" @change="handleFileInput" hidden
                            ref="fileInput" />
                        <button class="button is-primary is-large" @click="openFilePicker">
                            Select Files
                        </button>
                    </div>
                </div>

                <div v-if="files.length" class="mt-6">
                    <h2 class="subtitle">Selected Files</h2>
                    <div class="table-container">
                        <table class="table is-striped is-fullwidth">
                            <thead>
                                <tr>
                                    <th>File Name</th>
                                    <th>Format</th>
                                    <th>Size</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr v-for="(file, index) in files" :key="index">
                                    <td>{{ file.name }}</td>
                                    <td>
                                        <div class="select">
                                            <select v-model="file.targetFormat"
                                                @change="updateFormat(index, file.targetFormat)">
                                                <option value="jpeg">JPEG</option>
                                                <option value="png">PNG</option>
                                                <option value="webp">WEBP</option>
                                                <option value="gif">GIF</option>
                                                <option value="bmp">BMP</option>
                                                <option value="avif">AVIF</option>
                                            </select>
                                        </div>
                                    </td>
                                    <td>{{ (file.size / 1024).toFixed(2) }} KB</td>
                                    <td v-if="file.convertedUrl">
                                        <a :href="file.convertedUrl" :download="file.convertedUrl"
                                            class="button is-success">
                                            Download
                                        </a>
                                    </td>
                                    <td v-else>
                                        <button class="button is-danger" @click="removeFile(index)">
                                            Remove
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <div class="has-text-centered">
                        <button class="button is-success is-large" :disabled="isConverting" @click="convertAll">
                            <span v-if="!isConverting">Convert All</span>
                            <span v-else>
                                <i class="fas fa-spinner fa-spin"></i> Converting...
                            </span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            files: [],
            isConverting: false,
        };
    },
    computed: {
        /**
         * Retorna o link de conversão baseado nos formatos de origem e destino dos arquivos selecionados.
         */
        conversionLink() {
            if (this.files.length === 1) {
                const sourceFormat = this.files[0].format;
                const targetFormat = this.files[0].targetFormat;
                return `${sourceFormat}-to-${targetFormat}`;
            }

            const uniqueSourceFormats = [
                ...new Set(this.files.map((file) => file.format)),
            ];
            const uniqueTargetFormats = [
                ...new Set(this.files.map((file) => file.targetFormat)),
            ];

            if (uniqueSourceFormats.length === 1 && uniqueTargetFormats.length === 1) {
                return `${uniqueSourceFormats[0]}-to-${uniqueTargetFormats[0]}`;
            }

            return "";
        },
    },
    watch: {
        /**
         * Observa mudanças no link de conversão e atualiza a rota.
         */
        conversionLink(newLink) {
            this.$router.push({ path: `/converter/${newLink}` });

            const formattedTitle = newLink
                .split('-') // Divide o newLink por '-'
                .map(word => word.charAt(0).toUpperCase() + word.slice(1)) // Transforma cada palavra em maiúsculas
                .join(' '); // Junta as palavras com espaço

            document.title = `${formattedTitle} | Online Image Converter | Effortless Image Format Conversion`;
        },
    },
    methods: {
        /**
         * Abre o seletor de arquivos para o usuário.
         */
        openFilePicker() {
            this.$refs.fileInput.click();
        },
        /**
         * Lida com a entrada de arquivos no seletor.
         */
        handleFileInput(event) {
            const selectedFiles = Array.from(event.target.files);
            this.addFiles(selectedFiles);
        },
        /**
         * Lida com o evento de "arrastar e soltar" arquivos.
         */
        handleDrop(event) {
            const droppedFiles = Array.from(event.dataTransfer.files);
            this.addFiles(droppedFiles);
        },
        /**
         * Adiciona arquivos selecionados à lista de arquivos para conversão.
         */
        addFiles(newFiles) {
            newFiles.forEach((file) => {
                const fileExtension = file.name.split(".").pop().toLowerCase();
                const existingFile = this.files.find((f) => f.name === file.name);

                const url = window.location.href;
                const match = url.match(/\/(\w+)-to-(\w+)/);
                let formatoDesejado = 'jpeg';

                if (match) {
                    const [_, sourceFormat, targetFormat] = match;
                    formatoDesejado = targetFormat;
                }

                if (!existingFile) {
                    this.files.push({
                        file,
                        name: file.name,
                        size: file.size,
                        format: fileExtension,
                        targetFormat: formatoDesejado,
                    });
                }
            });
        },
        /**
         * Converte todos os arquivos selecionados usando a API.
         */
        async convertAll() {
            this.isConverting = true;

            try {
                for (const fileObj of this.files) {
                    const formData = new FormData();
                    formData.append("arquivos[]", fileObj.file);
                    formData.append("formatos[]", fileObj.targetFormat);

                    try {
                        const response = await axios.post("/api/conversor-imagem", formData, {
                            timeout: 3000,
                            headers: {
                                "Content-Type": "multipart/form-data",
                            },
                        });

                        if (response.data && response.data.convertedFiles && response.data.convertedFiles[0]) {
                            fileObj.convertedUrl = response.data.convertedFiles[0]['arquivo'];
                        } else {
                            throw new Error("Formato de resposta inesperado.");
                        }
                    } catch (error) {
                        console.error("Erro ao converter o arquivo:", fileObj.file.name, error);
                        alert(`Ocorreu um erro na conversão de ${fileObj.file.name}. ${error} Tente novamente.`);
                    }
                }
            } catch (error) {
                console.error("Erro no processo de conversão:", error);
                alert("Ocorreu um erro no processo de conversão. Tente novamente.");
            } finally {
                this.isConverting = false;
            }
        },
        /**
         * Remove um arquivo da lista.
         */
        removeFile(index) {
            this.files.splice(index, 1);
        },
        /**
         * Atualiza o formato de destino de um arquivo.
         */
        updateFormat(index, newFormat) {
            this.files[index].targetFormat = newFormat;
        },
    },
};
</script>


<style scoped>
.is-droppable {
    border: 2px dashed #00d1b2;
    padding: 2rem;
    border-radius: 6px;
    transition: background-color 0.3s ease;
}

.is-droppable:hover {
    background-color: #f5f5f5;
}

.box {
    margin-left: 2rem !important;
    margin-right: 2rem !important;
}

/* Estilo geral para manter a página limpa e profissional */
body {
    background-color: #f9f9f9;
    font-family: 'Arial', sans-serif;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Estilo para o título e subtítulo */
h1 {
    font-size: 2rem !important;
    font-weight: bold;
    color: #333;
    text-align: center;
    margin-bottom: 0.5rem;
}

p.subtitle {
    text-align: center;
    color: #666;
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

/* Estilo da área de upload (drag and drop) */
.is-droppable {
    border: 2px dashed #00b894;
    background-color: #fff;
    border-radius: 10px;
    padding: 2rem;
    text-align: center;
    transition: all 0.3s ease;
}

.is-droppable:hover {
    background-color: #eafaf1;
    border-color: #00d1b2;
}

.is-droppable p {
    font-size: 1.2rem;
    color: #333;
    margin-bottom: 1rem;
    font-weight: bold;
}

.is-droppable button {
    font-size: 1rem;
    padding: 0.75rem 1.5rem;
    color: white;
    background-color: #00b894;
    border: none;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
}

.is-droppable button:hover {
    background-color: #00916e;
}

/* Tabela de arquivos */
.table-container {
    margin-top: 2rem;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
    overflow: hidden;
}

table {
    width: 100%;
    border-collapse: collapse;
    background: white;
}

table th,
table td {
    padding: 1rem;
    text-align: left;
    font-size: 1rem;
    color: #555;
    border-bottom: 1px solid #eee;
}

table th {
    background-color: #f3f3f3;
    font-weight: bold;
    color: #333;
}

table tr:hover {
    background-color: #f9f9f9;
}

button.is-danger {
    background-color: #e74c3c;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 0.5rem 1rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button.is-danger:hover {
    background-color: #c0392b;
}

/* Botão de conversão */
button.is-success {
    margin-top: 2rem;
    background-color: #27ae60;
    color: white;
    border: none;
    border-radius: 5px;
    padding: 0.75rem 1.5rem;
    font-size: 1.25rem;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

button.is-success:hover {
    background-color: #1e8449;
}

/* Ajustes responsivos */
@media (max-width: 768px) {
    h1 {
        font-size: 2rem;
    }

    .is-droppable {
        padding: 1.5rem;
    }

    table th,
    table td {
        font-size: 0.9rem;
        padding: 0.75rem;
    }
}

a.button {
    display: inline-block;
    padding: 0.5rem 1rem;
    text-align: center;
    color: white;
    background-color: #27ae60;
    border: none;
    border-radius: 5px;
    text-decoration: none;
    font-size: 0.875rem;
}

a.button:hover {
    background-color: #1e8449;
}

.select select {
    color: #000000;
    background-color: white;
    border: 1px solid #dbdbdb;
    font-size: 1rem;
    border-radius: 4px;
    appearance: none;
}

.select select:focus {
    border-color: #00d1b2;
    box-shadow: 0 0 0 0.125em rgba(0, 209, 178, 0.25);
    outline: none;
}

.table.is-striped tbody tr:nth-child(odd) {
    background-color: #f8f9fa !important;
    /* Cor clara para linhas ímpares */
}

.table.is-striped tbody tr:nth-child(even) {
    background-color: #ffffff !important;
    /* Cor branca para linhas pares */
}

.table.is-striped tbody tr:hover {
    background-color: #e2e6ea !important;
    /* Cor de destaque ao passar o mouse */
}

.table th {
    color: #000000;
    /* Define o texto em preto */
    font-weight: bold;
    /* Opcional: mantém o destaque */
    background-color: #f3f3f3;
    /* Garante um fundo claro */
    text-align: left;
    /* Mantém o alinhamento */
}
</style>
