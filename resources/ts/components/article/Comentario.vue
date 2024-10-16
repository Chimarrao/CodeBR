<template>
    <div class="box">
        <article class="media">
            <div class="media-content">
                <div class="content">
                    <strong
                        ><p>{{ comentario.nome }}</p></strong
                    >
                    <p>{{ comentario.comentario }}</p>
                </div>
                <button
                    @click="mostrarFormularioResposta = !mostrarFormularioResposta"
                    class="button is-link is-small"
                >
                    Responder
                </button>

                <!-- Formulário de Resposta -->
                <form v-if="mostrarFormularioResposta" @submit.prevent="enviarResposta">
                    <div class="field is-horizontal">
                        <div class="field-body">
                            <div class="field">
                                <p class="label">Nome:</p>
                                <div class="control">
                                    <input
                                        v-model="respostaNome"
                                        class="input"
                                        name="nome"
                                        type="text"
                                        required
                                    />
                                </div>
                            </div>
                            <div class="field">
                                <p class="label">Email (não será publicado):</p>
                                <div class="control">
                                    <input
                                        v-model="respostaEmail"
                                        class="input"
                                        name="email"
                                        type="email"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="field">
                        <p class="label">Comentário:</p>
                        <div class="control">
                            <textarea
                                v-model="respostaComentario"
                                class="textarea"
                                name="comentario"
                                required
                            ></textarea>
                        </div>
                    </div>
                    <div class="control">
                        <button class="button is-primary" type="submit">
                            Enviar Resposta
                        </button>
                    </div>
                </form>

                <!-- Respostas -->
                <div v-if="comentario.respostas && comentario.respostas.length">
                    <Resposta
                        v-for="resposta in comentario.respostas"
                        :key="resposta.id"
                        :resposta="resposta"
                    />
                </div>
            </div>
        </article>
    </div>
</template>

<script>
import axios from "axios";
import { ref } from "vue";
import Resposta from "./Resposta.vue";

export default {
    components: { Resposta },
    props: {
        comentario: Object,
    },
    setup(props) {
        const mostrarFormularioResposta = ref(false);
        const respostaNome = ref("");
        const respostaEmail = ref("");
        const respostaComentario = ref("");

        const enviarResposta = async () => {
            const respostaEnviar = {
                nome: respostaNome.value,
                email: respostaEmail.value,
                comentario: respostaComentario.value,
                id_comentario_resposta: props.comentario.id,
            };

            try {
                const response = await axios.post("/api/comentarios", respostaEnviar);
                // Adiciona a resposta ao comentário atual
                props.comentario.respostas.push(response.data);
                // Limpa o formulário após o envio
                respostaNome.value = "";
                respostaEmail.value = "";
                respostaComentario.value = "";
                mostrarFormularioResposta.value = false;
            } catch (error) {
                console.error("Erro ao enviar resposta", error);
            }
        };

        return {
            mostrarFormularioResposta,
            respostaNome,
            respostaEmail,
            respostaComentario,
            enviarResposta,
        };
    },
};
</script>
