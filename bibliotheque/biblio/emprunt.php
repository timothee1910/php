<?php
// 1 connexion BDD
require_once('init.inc.php');

$active = 'emprunt'; // indique le fichier dans lequel on se trouve, pour activer le <LI> du menu

if(!empty($_GET['delete']) && is_numeric($_GET['delete'])) {
    // je prepare la requete
    $delete = $pdo->prepare('DELETE FROM emprunt WHERE id_emprunt = :id');

// j'indique a PDO, que :id correspond a $_GET['delete'], il va assainir le $_GET en s'assurant que c'est bien un INTEGER et rien d'autre.
    $delete->bindValue(':id', $_GET['delete'], PDO::PARAM_INT);

// j'execute la requete
    $delete->execute();
}


// 1 je recupere les infos de mon $_POST
$msg = '';
if(
    isset($_POST['enregistrer'])
    && !empty($_POST['id_livre'])
    && !empty($_POST['id_abonne'])
    && !empty($_POST['date_sortie'])
) {

    // je verifie si mes 2 champs contiennent au moins une lettre
    if(is_numeric($_POST['id_livre']) && is_numeric($_POST['id_abonne'])){
        // 2 je prepare ma requete
        $insert = $pdo->prepare('INSERT INTO emprunt(id_livre, id_abonne, date_sortie, date_rendu) VALUES(:id_livre, :id_abonne, :date_sortie, :date_rendu)');
        // 3 je lie ma variable SQL a ma variable PHP $_POST
        $insert->bindValue(':id_livre', $_POST['id_livre'], PDO::PARAM_INT);
        $insert->bindValue(':id_abonne', $_POST['id_abonne'], PDO::PARAM_INT);
        $insert->bindValue(':date_sortie', $_POST['date_sortie'], PDO::PARAM_STR);
        $insert->bindValue(':date_rendu', $_POST['date_rendu'], PDO::PARAM_STR);
        // 4 j'execute ma requete
        $insert->execute();
    } else {
        $msg = '<p class="alert alert-danger">Erreur veuillez verifier vos champs</p>';
    }
}

if(!empty($_GET['modif']) && is_numeric($_GET['modif'])) {
    // requete de recuperation des donnees pour affichage
    $getRow = $pdo->prepare('SELECT * FROM emprunt WHERE id_emprunt = :id');
    $getRow->bindValue(':id', $_GET['modif'], PDO::PARAM_INT);
    $getRow->execute();
    $resultToModify = $getRow->fetch(PDO::FETCH_ASSOC);
}

// cas de modification
if(isset($_POST['modifier'])
    && !empty($_POST['id_emprunt'])
    && !empty($_POST['id_livre'])
    && !empty($_POST['id_abonne'])
    && !empty($_POST['date_sortie'])
    && is_numeric($_POST['id_emprunt'])
    && is_numeric($_POST['id_livre'])
    && is_numeric($_POST['id_abonne'])
) {
    $update = $pdo->prepare('UPDATE emprunt SET id_abonne = :id_abonne, id_livre = :id_livre, date_sortie = :date_sortie, date_rendu = :date_rendu WHERE id_emprunt = :id');
    $update->bindValue(':id_abonne', $_POST['id_abonne'], PDO::PARAM_INT);
    $update->bindValue(':id_livre', $_POST['id_livre'], PDO::PARAM_INT);
    $update->bindValue(':date_sortie', $_POST['date_sortie'], PDO::PARAM_STR);

    $date_rendu = (!empty($_POST['date_rendu'])) ? $_POST['date_rendu'] : 'NULL';
    $update->bindValue(':date_rendu', $date_rendu, PDO::PARAM_STR);

    $update->bindValue(':id', $_POST['id_emprunt'], PDO::PARAM_INT);
    $update->execute();

    header('location:'. $_SERVER['PHP_SELF']);
    exit();
}


// affichage de la table emprunt
$emprunt = $pdo->query('SELECT * FROM emprunt');
$emprunts = $emprunt->fetchAll(PDO::FETCH_ASSOC);

// recuperation des prenoms de la table abonne
$abonne = $pdo->query('SELECT * FROM abonne');
$abonnes = $abonne->fetchAll(PDO::FETCH_ASSOC);

// recuperation des auteurs et titres de la table livre
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

    <?php if( !empty($emprunts) && is_array($emprunts) ) : ?>

        <table style="margin-top: 50px;" class="table table-striped">
            <tr>
                <?php
                foreach ($emprunts[0] as $key => $value) :
                    ?>
                    <th><?= $key ?></th>
                    <?php
                endforeach;
                ?>
                <th>modification</th>
                <th>suppression</th>
            </tr>
            <?php
            foreach ($emprunts as $key => $value) :
                ?>
                <tr>
                    <td><?= $value['id_emprunt']?></td>
                    <td><?= $value['id_livre']?></td>
                    <td><?= $value['id_abonne']?></td>
                    <td><?= $value['date_sortie']?></td>
                    <td><?= $value['date_rendu']?></td>
                    <td>
                        <a href="?modif=<?= $value['id_emprunt'] ?>">
                            <span class="glyphicon glyphicon-pencil"></span>
                        </a>
                    </td>
                    <td>
                        <a href="?delete=<?= $value['id_emprunt'] ?>">
                            <span class="glyphicon glyphicon-remove-sign"></span>
                        </a>
                    </td>
                </tr>
                <?php
            endforeach;

            ?>
        </table>
    <?php else: ?>
        <p style="margin-top: 100px; text-align: center; font-weight: bold;">Tu n'as pas d'emprunts ! :)</p>
    <?php endif; ?>

    <form class="form" action="" method="post">
        <select name="id_abonne" class="form-control">
            <?php foreach ($abonnes as $key => $value) : ?>
                <option
                    <?=
                    (!empty($resultToModify)
                        && $resultToModify['id_abonne'] == $value['id_abonne']
                    ) ? 'selected': '' ?>
                        value="<?= $value['id_abonne'] ?>">
                    <?= $value['id_abonne'] ?> - <?= $value['prenom'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <select name="id_livre" class="form-control">
            <?php foreach ($livres as $key => $value) : ?>
                <option
                    <?=
                    (!empty($resultToModify)
                        && $resultToModify['id_livre'] == $value['id_livre']
                    ) ? 'selected': '' ?>
                        value="<?= $value['id_livre'] ?>">
                    <?= $value['id_livre'] ?> - <?= $value['auteur'] ?> | <?= $value['titre'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
        <label for="date_sortie">date sortie</label>
        <br>
        <input
                value="<?= (!empty($resultToModify)) ? $resultToModify['date_sortie'] : '' ?>"
                class="form-control"
                type="date"
                name="date_sortie">
        <br>
        <label for="date_rendu">date rendu</label>
        <br>
        <input
                value="<?= (!empty($resultToModify)) ? $resultToModify['date_rendu'] : '' ?>"
                class="form-control"
                type="date"
                name="date_rendu">
        <br>
        <?php if(!empty($resultToModify)) : ?>
            <input type="hidden"
                   name="id_emprunt" value="<?= $resultToModify['id_emprunt'] ?>">
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
