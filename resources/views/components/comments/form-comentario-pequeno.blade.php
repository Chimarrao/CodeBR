<form id="resposta_form_{{ $comentario->id }}" method="POST" style="display: none">
    <div class="field is-horizontal">
        <div class="field-body">
            <div class="field">
                <p class="label">Nome:</p>
                <div class="control">
                    <input class="input" name="nome" type="text" required>
                </div>
            </div>
            <div class="field">
                <p class="label">Email (não será publicado):</p>
                <div class="control">
                    <input class="input" name="email" type="email">
                </div>
            </div>
        </div>
    </div>

    <div class="field">
        <p class="label">Comentário:</p>
        <div class="control">
            <textarea class="textarea" name="comentario" required></textarea>
        </div>
    </div>

    <div class="field">
        <div class="g-recaptcha" data-sitekey="6LdlmB4pAAAAAF8uCw8BeWogDClVSiCRx5eNx-7e"></div>
    </div>

    <div class="control">
        <button class="button is-primary" type="submit">Enviar Comentário</button>
    </div>
</form>