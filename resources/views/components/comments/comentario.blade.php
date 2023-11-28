<div class="box">
    <article class="media">
        <div class="media-content">
            <div class="content">
                <strong>
                    <p>{{ $comentario->nome }}</p>
                </strong>
                <p>{{ $comentario->comentario }}</p>
            </div>
            <button class="button is-link is-small responder" id_comentario="{{ $comentario->id }}">Responder</button>
            @include('components.comments.form-comentario-pequeno')
        </div>
    </article>

    <div id="respostas_{{ $comentario->id }}">
        @if (count($comentario->respostas))
            @foreach ($comentario->respostas as $resposta)
                @include('components.comments.resposta')
            @endforeach
        @endif
    </div>
</div>
