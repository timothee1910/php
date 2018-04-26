<?php
try
{

    $pdo = new PDO('mysql:host=localhost;dbname=tong_bibliotheque','tong','mn3XHxjP57', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING, PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));

}
catch (Exception $e){
    die('Erreur : '.$e->getMessage());
}

?>
/**
 * Created by PhpStorm.
 * User: ongtimothee
 * Date: 27/09/2017
 * Time: 14:44
 */
