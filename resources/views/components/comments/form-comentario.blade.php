<div class="message-body">
    <form id="comentarioForm" method="POST">
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
        <div class="field">
            <p class="label">Comentário:</p>
            <div class="control">
                <textarea class="textarea" name="comentario" required></textarea>
            </div>
        </div>
        <div class="field">
            <div class="g-recaptcha" data-sitekey="6LeJkxwpAAAAAG3I6h3cfBxaVFqMC4NHpCVc0sw8"></div>
        </div>
        <div class="control">
            <button class="button is-primary" type="submit">Enviar Comentário</button>
        </div>
    </form>
</div>
