<div class="box">
    <article class="media">
        <div class="media-content">
            <div class="content">
                <strong>
                    <p>{{ $comentario->nome }}</p>
                </strong>
                <p>{{ $comentario->comentario }}</p>
            </div>
            <button class="button is-link is-small" onclick="toggleResposta('resposta1')">Responder</button>
            @include('components.comments.form-comentario-pequeno')
        </div>
    </article>

    @if (count($comentario->respostas))
        @foreach ($comentario->respostas as $resposta)
            @include('components.comments.resposta')
        @endforeach
    @endif
</div>
