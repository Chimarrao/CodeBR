<div class="box">
    <article class="media">
        <div class="media-content">
            <div class="content">
                <strong>
                    <p>Nome do Autor 1</p>
                </strong>
                <p>
                    Comentário 1 Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer
                    posuere erat a ante.
                </p>
            </div>
            <button class="button is-link is-small" onclick="toggleResposta('resposta1')">Responder</button>
            @include('components.comments.form-comentario-pequeno')
        </div>
    </article>

    @include('components.comments.resposta')
</div>
