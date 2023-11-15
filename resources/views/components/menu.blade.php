<nav class="navbar" role="navigation" aria-label="main navigation">
    <div class="navbar-brand">
        <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
            <span aria-hidden="true"></span>
        </a>
    </div>

    <div id="navbarBasicExample" class="navbar-menu">
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
                        <input name="q" class="input" type="text" placeholder="Pesquisar...">
                    </p>
                    <p class="control">
                        <button class="button is-dark">Pesquisar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
</nav>