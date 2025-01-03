<template>
    <Menu />
    <Cabecalho />

    <section>
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-two-thirds">
                    <div class="message-body mt-6">
                        <form @submit.prevent="submitForm">
                            <div class="field">
                                <p class="label">Nome:</p>
                                <div class="control">
                                    <input class="input" v-model="form.nome" type="text" name="nome" required>
                                </div>
                            </div>

                            <div class="field">
                                <p class="label">Email:</p>
                                <div class="control">
                                    <input class="input" v-model="form.email" type="email" name="email">
                                </div>
                            </div>

                            <div class="field">
                                <p class="label">Telefone:</p>
                                <div class="control">
                                    <input class="input" v-model="form.telefone" type="tel" name="telefone">
                                </div>
                            </div>

                            <div class="field">
                                <p class="label">Mensagem:</p>
                                <div class="control">
                                    <textarea class="textarea" v-model="form.mensagem" name="mensagem" required></textarea>
                                </div>
                            </div>

                            <div class="field">
                                <!-- Recaptcha vai aqui -->
                                <div class="g-recaptcha" data-sitekey="6LdlmB4pAAAAAF8uCw8BeWogDClVSiCRx5eNx-7e"></div>
                            </div>

                            <div class="control">
                                <button class="button is-primary" type="submit" :disabled="isSubmitting">Enviar</button>
                            </div>
                        </form>
                        <div v-if="alert.message" :class="`notification is-${alert.type}`">{{ alert.message }}</div>
                    </div>
                </div>
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
            form: {
                nome: '',
                email: '',
                telefone: '',
                mensagem: '',
            },
            isSubmitting: false,
            alert: {
                type: '',
                message: '',
            },
        };
    },
    methods: {
        async submitForm() {
            this.isSubmitting = true;
            this.alert = { type: '', message: '' };

            const recaptchaElement = document.querySelector(
                'textarea[name="g-recaptcha-response"]'
            );
            const recaptchaResponse =
                recaptchaElement instanceof HTMLTextAreaElement
                    ? recaptchaElement.value
                    : '';

            if (!recaptchaResponse) {
                this.alert = {
                    type: 'danger',
                    message: 'Por favor, marque "Não sou um robô".',
                };
                this.isSubmitting = false;
                return;
            }

            const mensagemEnviar = {
                nome: this.form.nome,
                email: this.form.email,
                telefone: this.form.telefone,
                mensagem: this.form.mensagem,
                g_recaptcha_response: recaptchaResponse,
            };

            try {
                const response = await axios.post('/api/contato', mensagemEnviar);

                if (response.status === 200) {
                    this.alert = {
                        type: 'success',
                        message: 'Mensagem enviada com sucesso!',
                    };
                    this.form = {
                        nome: '',
                        email: '',
                        telefone: '',
                        mensagem: '',
                    };
                } else {
                    this.alert = {
                        type: 'danger',
                        message: 'Erro! Tente novamente.',
                    };
                }
            } catch (error) {
                console.error('Erro na requisição:', error);
                this.alert = {
                    type: 'danger',
                    message: 'Ocorreu um erro ao enviar a mensagem.',
                };
            } finally {
                this.isSubmitting = false;
            }
        },
    },
};
</script>