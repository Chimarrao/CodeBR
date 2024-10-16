<template>
    <div class="message-body">
        <form @submit.prevent="enviarComentario">
            <div class="field">
                <p class="label">Nome:</p>
                <div class="control">
                    <input
                        v-model="nome"
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
                    <input v-model="email" class="input" name="email" type="email" />
                </div>
            </div>
            <div class="field">
                <p class="label">Comentário:</p>
                <div class="control">
                    <textarea
                        v-model="comentario"
                        class="textarea"
                        name="comentario"
                        required
                    ></textarea>
                </div>
            </div>
            <div class="field">
                <div
                    class="g-recaptcha"
                    data-sitekey="6LdlmB4pAAAAAF8uCw8BeWogDClVSiCRx5eNx-7e"
                ></div>
            </div>
            <div class="control">
                <button class="button is-primary" type="submit">Enviar Comentário</button>
            </div>
        </form>
    </div>
</template>

<script>
import { alerts } from './../../alerts/alerts';
import axios from "axios";
import { ref } from "vue";

export default {
    setup(props, { emit }) {
        const nome = ref("");
        const email = ref("");
        const comentario = ref("");

        const enviarComentario = async () => {
            const gRecaptchaResponse = document.querySelector(
                'textarea[name="g-recaptcha-response"]'
            ).value;

            const comentarioEnviar = {
                nome: nome.value,
                email: email.value,
                comentario: comentario.value,
                g_recaptcha_response: gRecaptchaResponse,
                url: window.location.pathname.split("/artigo/")[1].split("/")[0],
            };

            try {
                alerts._({ tipo: 'spinner' });
                const response = await axios.post("/api/comentarios", comentarioEnviar);
                alerts.off();

                if (response.data.erro) {
                    alerts._({ tipo: 'erro', mensagem: 'Erro! Marque a caixa "Não sou um robô"' });
                    return;
                } else {
                    emit('comentarioEnviado', response.data);
                }

                nome.value = "";
                email.value = "";
                comentario.value = "";
            } catch (error) {
                alerts._({ tipo: 'erro', mensagem: 'Erro ao enviar comentário' });
            }
        };

        return { nome, email, comentario, enviarComentario };
    },
};
</script>
