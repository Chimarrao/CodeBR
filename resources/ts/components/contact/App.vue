<template>
    <Menu />
    <Cabecalho />

    <section class="hero is-medium is-dark is-bold">
        <div class="hero-body">
            <div class="container text-center">
                <h1 class="title">Code BR</h1>
                <h2 class="subtitle" id="subtitulo">Um blog sobre programação</h2>
            </div>
        </div>
    </section>

    <section>
        <div class="container">
            <div class="columns is-centered">
                <div class="column is-two-thirds">
                    <div class="message-body mt-6">
                        <form @submit.prevent="submitForm">
                            <div class="field">
                                <p class="label">Nome:</p>
                                <div class="control">
                                    <input class="input" v-model="form.nome" type="text" required>
                                </div>
                            </div>

                            <div class="field">
                                <p class="label">Email:</p>
                                <div class="control">
                                    <input class="input" v-model="form.email" type="email">
                                </div>
                            </div>

                            <div class="field">
                                <p class="label">Telefone:</p>
                                <div class="control">
                                    <input class="input" v-model="form.telefone" type="tel">
                                </div>
                            </div>

                            <div class="field">
                                <p class="label">Mensagem:</p>
                                <div class="control">
                                    <textarea class="textarea" v-model="form.mensagem" required></textarea>
                                </div>
                            </div>

                            <div class="field">
                                <!-- Recaptcha vai aqui -->
                                <div class="g-recaptcha" data-sitekey="6LdlmB4pAAAAAF8uCw8BeWogDClVSiCRx5eNx-7e"></div>
                            </div>

                            <div class="control">
                                <button class="button is-primary" type="submit">Enviar</button>
                            </div>
                        </form>
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

export default {
    components: {
        Menu,
        Rodape,
        Cabecalho
    },
    data() {
        return {
            form: {
                nome: '',
                email: '',
                telefone: '',
                mensagem: ''
            }
        };
    },
    methods: {
        async submitForm() {
            console.log("Formulário enviado", this.form);

            // const response = await axios.post('/api/contato', mensagem);
            // alerts.off();

            // if (response.status === 200) {
            //     formulario.reset();
            //     alerts._({ tipo: 'check', mensagem: 'Mensagem foi enviada com sucesso' });
            // } else {
            //     alerts._({ tipo: 'erro', mensagem: 'Erro! Marque a caixa "Não sou um robô"' });
            // }
        }
    }
};
</script>