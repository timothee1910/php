<nav class="navbar navbar-inverse navbar-fixed-top">
    <div class="container">
        <?= $msg ?>
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Bibliotheque</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li <?= (!empty($active) && $active == 'abonne') ? 'class="active"' : ''?>><a href="/perso/bibliotheque/biblio">Abonne</a></li>
                <li <?= (!empty($active) && $active == 'livre') ? 'class="active"' : ''?>><a href="/perso/bibliotheque/biblio/livre.php">Livre</a></li>
                <li <?= (!empty($active) && $active == 'emprunt') ? 'class="active"' : ''?>><a href="/perso/bibliotheque/biblio/emprunt.php">Emprunt</a></li>
            </ul>
        </div><!--/.nav-collapse -->
    </div>
</nav>