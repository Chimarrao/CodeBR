<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a class="navbar-burger" data-target="navbarBasicExample" role="button" aria-label="menu" aria-expanded="false">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div class="navbar-menu" id="navbarBasicExample">
        <div class="navbar-start">
            <a class="navbar-item has-text-white" href="{{ url('/') }}">Início</a>
            <a class="navbar-item has-text-white" href="{{ url('/sobre') }}">Sobre</a>
            <a class="navbar-item has-text-white" href="{{ url('/contato') }}">Contato</a>
            <a class="navbar-item has-text-white" href="{{ url('/politica-de-privacidade') }}">Política de Privacidade</a>
        </div>

        <div class="navbar-item">
            <form action="/" method="GET">
                <div class="field is-grouped">
                    <p class="control">
                        <input class="input" name="q" type="text" placeholder="Pesquisar...">
                    </p>
                    <p class="control">
                        <button class="button is-dark">Pesquisar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</nav>
