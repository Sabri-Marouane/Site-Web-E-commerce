<?php

include 'connexion.php';

$sessionUser = '';
if (isset($_SESSION['user'])) {
    $sessionUser = $_SESSION['user'];
}

$tpl='Includes/Templates/';
$func='Includes/Functions/';
$css='Layout/CSS/';
$img='Layout/images/';
$imgp='Upload/Produits/';
$js='Layout/JS/';

include $func .'function.php';
include $tpl.'Header.php';
if (!isset($nonavbar)) {
    include $tpl .'navbar.php';
}
?>