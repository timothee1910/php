<?php
// 1 connexion BDD
require_once('init.inc.php');

$active = 'livre'; // indique le fichier dans lequel on se trouve, pour activer le <LI> du menu

if(!empty($_GET['delete']) && is_numeric($_GET['delete'])) {
    // je prepare la requete
    $delete = $pdo->prepare('DELETE FROM livre WHERE id_livre = :id');

// j'indique a PDO, que :id correspond a $_GET['delete'], il va assainir le $_GET en s'assurant que c'est bien un INTEGER et rien d'autre.
    $delete->bindValue(':id', $_GET['delete'], PDO::PARAM_INT);

// j'execute la requete
    $delete->execute();
}


// 1 je recupere les infos de mon $_POST
$msg = '';
if(isset($_POST['enregistrer']) && !empty($_POST['auteur'])) {

    // je verifie si mes 2 champs contiennent au moins une lettre
    if(
        preg_match('/[a-zA-Z]/', $_POST['auteur'])
        && preg_match('/[a-zA-Z]/', $_POST['titre'])
    ){
        // 2 je prepare ma requete
        $insert = $pdo->prepare('INSERT INTO livre(auteur, titre) VALUES(:auteur, :titre)');
        // 3 je lie ma variable SQL a ma variable PHP $_POST
        $insert->bindValue(':auteur', $_POST['auteur'], PDO::PARAM_STR);
        $insert->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
        // 4 j'execute ma requete
        $insert->execute();
    } else {
        $msg = '<p class="alert alert-danger">Il faut au moins une lettre pour les 2 champs</p>';
    }
}

if(!empty($_GET['modif']) && is_numeric($_GET['modif'])) {
    // requete de recuperation des donnees pour affichage
    $getRow = $pdo->prepare('SELECT * FROM livre WHERE id_livre = :id');
    $getRow->bindValue(':id', $_GET['modif'], PDO::PARAM_INT);
    $getRow->execute();
    $resultToModify = $getRow->fetch(PDO::FETCH_ASSOC);
}

// cas de modification
if(isset($_POST['modifier'])
    && !empty($_POST['auteur'])
    && !empty($_POST['titre'])
    && !empty($_POST['id_livre'])
    && is_numeric($_POST['id_livre'])
) {
    $update = $pdo->prepare('UPDATE livre SET auteur = :auteur, titre = :titre WHERE id_livre = :id');
    $update->bindValue(':auteur', $_POST['auteur'], PDO::PARAM_STR);
    $update->bindValue(':titre', $_POST['titre'], PDO::PARAM_STR);
    $update->bindValue(':id', $_POST['id_livre'], PDO::PARAM_INT);
    $update->execute();

    header('location:'. $_SERVER['PHP_SELF']);
    exit();
}


// affichage de la table livre
$livre = $pdo->query('SELECT * FROM livre');
$livres = $livre->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Bibliotheque</title>

    <!-- Bootstrap core CSS -->
    <link href="../bootstrap/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<?php include('menu.php') ?>

<div class="container">

    <table style="margin-top: 50px;" class="table table-striped">
        <tr>
            <?php
            foreach ($livres[0] as $key => $value) :
                ?>
                <th><?= $key ?></th>
                <?php
            endforeach;
            ?>
            <th>modification</th>
            <th>suppression</th>
        </tr>
        <?php
        foreach ($livres as $key => $value) :
            ?>
            <tr>
                <td><?= $value['id_livre']?></td>
                <td><?= $value['auteur']?></td>
                <td><?= $value['titre']?></td>
                <td>
                    <a href="?modif=<?= $value['id_livre'] ?>">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                </td>
                <td>
                    <a href="?delete=<?= $value['id_livre'] ?>">
                        <span class="glyphicon glyphicon-remove-sign"></span>
                    </a>
                </td>
            </tr>
            <?php
        endforeach;

        ?>
    </table>

    <form class="form" action="" method="post">
        <label for="auteur">auteur</label>
        <br>
        <input
                value="<?= (!empty($resultToModify)) ? $resultToModify['auteur'] : '' ?>"
                class="form-control"
                type="text"
                name="auteur">
        <br>
        <label for="titre">titre</label>
        <br>
        <input
                value="<?= (!empty($resultToModify)) ? $resultToModify['titre'] : '' ?>"
                class="form-control"
                type="text"
                name="titre">
        <br>
        <?php if(!empty($resultToModify)) : ?>
            <input type="hidden"
                   name="id_livre" value="<?= $resultToModify['id_livre'] ?>">
        <?php endif; ?>
        <button
                name="<?= (!empty($resultToModify)) ? 'modifier' : 'enregistrer' ?>"
                type="submit" class="btn" >
            <?= (!empty($resultToModify)) ? 'modifier' : 'enregistrer' ?>
        </button>
    </form>

</div><!-- /.container -->


<!-- Bootstrap core JavaScript
================================================== -->
<!-- Placed at the end of the document so the pages load faster -->
<script src="http://code.jquery.com/jquery-latest.min.js"></script>
<script src="../bootstrap/js/bootstrap.min.js"></script>
<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="../bootstrap/assets/js/ie10-viewport-bug-workaround.js"></script>
</body>
</html>
