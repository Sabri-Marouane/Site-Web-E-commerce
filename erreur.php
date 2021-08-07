<?php 
ob_start();
$nonavbar='';
$pageTitle='Error';
include 'init.php'; 
?>
<div class="page-erreur">
    <div class="container-erreur" id="pageerreur">
        <div class="content-erreur">
            <h2 class="erreur-h2">404</h2>
            <h4 class="erreur-h4">Oups! Page non trouvée</h4>
            <p class="erreur-p">
                La page que vous cherchiez n'existe pas. Vous avez peut-être mal saisi l'adresse ou la page a pu être déplacée.
            </p>
            <a class="erreur-a" href="index.php">Retour à page d'accueil</a>
        </div>
    </div>
</div>
<?php 
include $tpl.'Footer.php'; 
ob_end_flush();
?>