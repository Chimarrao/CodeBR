<div class="column is-4">
    <a href="{{ url('artigo/' . $artigo->url) }}">
        <div class="box article">
            <article>
                <figure class="image is-3by2">
                    <img src="{{ asset($artigo->imagem) }}" alt="Imagem de Exemplo">
                </figure>
                <div class="content">
                    <h3 class="title is-4"><?= $artigo->artigo ?></h3>
                    <p><?= $artigo->descricao ?></p>
                    <p>Por: <?= $artigo->autor ?></p>
                    <p>📅 <?= (new IntlDateFormatter('pt_BR', IntlDateFormatter::LONG, IntlDateFormatter::NONE))->format((new DateTime($artigo->data_publicacao))->getTimestamp()) ?></p>
                </div>
            </article>
        </div>
    </a>
</div>