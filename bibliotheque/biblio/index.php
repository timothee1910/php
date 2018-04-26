<?php
// 1 connexion BDD
require_once('init.inc.php');
$active = 'abonne'; // indique le fichier dans lequel on se trouve, pour activer le <LI> du menu
if(!empty($_GET['delete']) && is_numeric($_GET['delete'])) {
    // je prepare la requete
    $delete = $pdo->prepare('DELETE FROM abonne WHERE id_abonne = :id');

// j'indique a PDO, que :id correspond a $_GET['delete'], il va assainir le $_GET en s'assurant que c'est bien un INTEGER et rien d'autre.
    $delete->bindValue(':id', $_GET['delete'], PDO::PARAM_INT);

// j'execute la requete
    $delete->execute();
}


// 1 je recupere les infos de mon $_POST
$msg = '';
if(isset($_POST['enregistrer']) && !empty($_POST['prenom'])) {

    if(preg_match('/[a-zA-Z]/', $_POST['prenom'])){
        // 2 je prepare ma requete
        $insert = $pdo->prepare('INSERT INTO abonne(prenom) VALUES(:prenom)');
        // 3 je lie ma variable SQL a ma variable PHP $_POST
        $insert->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        // 4 j'execute ma requete
        $insert->execute();
    } else {
        $msg = '<p class="alert alert-danger">Il faut au moins une lettre</p>';
    }
}

if(!empty($_GET['modif']) && is_numeric($_GET['modif'])) {
    // requete de recuperation des donnees pour affichage
    $getRow = $pdo->prepare('SELECT * FROM abonne WHERE id_abonne = :id');
    $getRow->bindValue(':id', $_GET['modif'], PDO::PARAM_INT);
    $getRow->execute();
    $resultToModify = $getRow->fetch(PDO::FETCH_ASSOC);
}

// cas de modification
if(isset($_POST['modifier'])
    && !empty($_POST['prenom'])
    && !empty($_POST['id_abonne'])
    && is_numeric($_POST['id_abonne'])
) {
    $update = $pdo->prepare('UPDATE abonne SET prenom = :prenom WHERE id_abonne = :id');
    $update->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
    $update->bindValue(':id', $_POST['id_abonne'], PDO::PARAM_INT);
    $update->execute();

    header('location:'. $_SERVER['PHP_SELF']);
    exit();
}


// affichage de la table abonne
$abonne = $pdo->query('SELECT * FROM abonne');
$abonnes = $abonne->fetchAll(PDO::FETCH_ASSOC);

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

<?php include ('menu.php') ?>;

<div class="container">

    <table style="margin-top: 50px;" class="table table-striped">
        <tr>
            <?php
            foreach ($abonnes[0] as $key => $value) :
                ?>
                <th><?= $key ?></th>
                <?php
            endforeach;
            ?>
            <th>modification</th>
            <th>suppression</th>
        </tr>
        <?php
        foreach ($abonnes as $key => $value):
            ?>
            <tr>
                <td><?= $value['id_abonne']?></td>
                <td><?= $value['prenom']?></td>
                <td>
                    <a href="?modif=<?= $value['id_abonne'] ?>">
                        <span class="glyphicon glyphicon-pencil"></span>
                    </a>
                </td>
                <td>
                    <a href="?delete=<?= $value['id_abonne'] ?>">
                        <span class="glyphicon glyphicon-remove-sign"></span>
                    </a>
                </td>
            </tr>
            <?php
        endforeach;

        ?>
    </table>

    <form class="form" action="" method="post">
        <label for="prenom">Prenom</label>
        <br>
        <input
                value="<?= (!empty($resultToModify)) ? $resultToModify['prenom'] : '' ?>"
                class="form-control"
                type="text"
                name="prenom">
        <br>
        <?php if(!empty($resultToModify)) : ?>
            <input type="hidden"
                   name="id_abonne" value="<?= $resultToModify['id_abonne'] ?>">
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
