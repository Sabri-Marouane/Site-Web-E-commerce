<?php
ob_start();
session_start();
$pageTitle='Produit';
include 'init.php';
?>
<?php
$itemid = isset($_GET['index']) && is_numeric($_GET['index']) ? intval($_GET['index']) :0;
if ($_SERVER['REQUEST_METHOD']=='POST') {
    if (isset($_SESSION['id'])){
       if(isset($_POST['panier'])){
            $image = $_POST['image'];
            $description = $_POST['description'];
            $size = $_POST['size'];
            $color = $_POST['color'];
            $quantite = $_POST['quantite'];
            $prixtotal = $_POST['quantite'] * $_POST['prix'];
            $panier_utilisateur	= $_SESSION['id'];
            $panier_produit = $itemid;

            $stmt=$cnx -> prepare("SELECT * FROM paniers WHERE size =? AND color =? AND panier_utilisateur =? AND panier_produit =?");
            $stmt -> execute(array($size,$color,$panier_utilisateur,$panier_produit));
            $count = $stmt -> rowCount();
            if ($count > 0){
                $stmt=$cnx->prepare("UPDATE paniers SET quantite=?, prixtotal=? WHERE panier_utilisateur=? AND panier_produit =?");
                $stmt->execute(array($quantite,$prixtotal,$panier_utilisateur,$panier_produit));
                if ($stmt) {
                    ?>
                    <script>
                        Swal.fire({
                        icon: 'success',
                        title: 'Good job!',
                        text: 'Un nouvel article a été ajouté à votre panier.',
                        footer: '<a class="href-panier" href="index.php">Tu veux voir votre panier ?</a>',
                        })
                    </script>
                    <?php 
                }
            }else {
                $stmt=$cnx->prepare("INSERT INTO paniers(image, description, size, color, quantite, prixtotal, date, panier_utilisateur, panier_produit) 
                    VALUES(:zimage, :zdescription, :zsize, :zcolor, :zquantite, :zprixtotal, now(), :zpanier_utilisateur, :zpanier_produit)");
                $stmt->execute(array(
                    'zimage' => $image,
                    'zdescription'  => $description,
                    'zsize' => $size,
                    'zcolor' => $color,
                    'zquantite' => $quantite,
                    'zprixtotal' => $prixtotal,
                    'zpanier_utilisateur' => $panier_utilisateur,
                    'zpanier_produit' => $panier_produit,
                ));
                if ($stmt) {
                    ?>
                    <script>
                        Swal.fire({
                        icon: 'success',
                        title: 'Good job!',
                        text: 'Un nouvel article a été ajouté à votre panier.',
                        footer: '<a class="href-panier" href="index.php">Tu veux voir votre panier ?</a>',
                        })
                    </script>
                    <?php 
                }
            }

            
        }elseif (isset($_POST['like'])) {

            $likevalue = $_POST['likevalue'];
            $like_utilisateur = $_SESSION['id'];
            $like_produit = $itemid;

            $stmt=$cnx -> prepare("SELECT * FROM likes WHERE like_produit =? AND like_utilisateur =?");
            $stmt -> execute(array($like_produit,$like_utilisateur));
            $count = $stmt -> rowCount();

            if ($count > 0){
                $stmt=$cnx->prepare("UPDATE likes SET liked=? WHERE like_produit=? AND like_utilisateur =?");
                $stmt->execute(array($likevalue,$like_produit,$like_utilisateur));
            }else {
                $stmt=$cnx->prepare("INSERT INTO likes(liked, date, like_utilisateur, like_produit) 
                    VALUES(:zliked, now(), :zlike_utilisateur, :zlike_produit)");
                $stmt->execute(array(
                    'zliked' => $likevalue,
                    'zlike_utilisateur' => $like_utilisateur,
                    'zlike_produit' => $like_produit,
                ));
            }
        }
    }
    else{
        header("Location: connecter.php");
        exit();
    }
}
?>
<?php
$stmt=$cnx -> prepare("SELECT * FROM produits WHERE produit_id =? AND active=1");
$stmt -> execute(array($itemid));
$items =$stmt->fetch();
$count = $stmt -> rowCount();
if ($count > 0) {
    ?>
    <form action="<?php echo $_SERVER['PHP_SELF'] . '?index=' . $items['produit_id']?>" method="POST">
        <div class="items">
            <div class="items-img">
                <div class="img-produit">
                    <img class="imggrand item-1 active-img" src="<?php echo $imgp . $items['image1'];?>" alt="">
                    <input type="hidden" name="image" value="<?php echo $items['image1'];?>">
                    <img class="imggrand item-2" src="<?php echo $imgp . $items['image2'];?>" alt="">
                    <img class="imggrand item-3" src="<?php echo $imgp . $items['image3'];?>" alt="">
                    <img class="imggrand item-4" src="<?php echo $imgp . $items['image4'];?>" alt="">
                    <img class="imggrand item-5" src="<?php echo $imgp . $items['image5'];?>" alt="">
                </div>
                <div class="img-les">
                    <ul class="les-ul">
                        <li class="les-li">
                            <div class="img-view">
                                <img class="imgpetite img-1" src="<?php echo $imgp . $items['image1'];?>" alt="">
                            </div>
                        </li>
                        <li class="les-li">
                            <div class="img-view">
                                <img class="imgpetite img-2" src="<?php echo $imgp . $items['image2'];?>" alt="">
                            </div>
                        </li>
                        <li class="les-li">
                            <div class="img-view">
                                <img class="imgpetite img-3" src="<?php echo $imgp . $items['image3'];?>" alt="">
                            </div>
                        </li>
                        <li class="les-li">
                            <div class="img-view">
                                <img class="imgpetite img-4" src="<?php echo $imgp . $items['image4'];?>" alt="">
                            </div>
                        </li>
                        <li class="les-li">
                            <div class="img-view">
                                <img class="imgpetite img-5" src="<?php echo $imgp . $items['image5'];?>" alt="">
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="items-info">
                <div class="paraghaphe">
                    <p><?php echo $items['description'];?></p>
                    <input type="hidden" name="description" value="<?php echo $items['description'];?>">
                </div>
                <div class="info-star">
                    <?php
                    switch ($items['status']) {
                        case $items['status'] == 5:
                            ?>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <?php
                            break;

                        case $items['status'] >= 4.5:
                            ?>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-half star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.354 5.119L7.538.792A.516.516 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.537.537 0 0 1 16 6.32a.55.55 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.519.519 0 0 1-.146.05c-.341.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.171-.403.59.59 0 0 1 .084-.302.513.513 0 0 1 .37-.245l4.898-.696zM8 12.027c.08 0 .16.018.232.056l3.686 1.894-.694-3.957a.564.564 0 0 1 .163-.505l2.906-2.77-4.052-.576a.525.525 0 0 1-.393-.288L8.002 2.223 8 2.226v9.8z"/>
                            </svg>
                            <?php
                            break;
                        
                        case $items['status'] >= 4:
                            ?>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                            </svg>
                            <?php
                            break;
                        
                        case $items['status'] >= 3.5:
                            ?>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-half star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.354 5.119L7.538.792A.516.516 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.537.537 0 0 1 16 6.32a.55.55 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.519.519 0 0 1-.146.05c-.341.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.171-.403.59.59 0 0 1 .084-.302.513.513 0 0 1 .37-.245l4.898-.696zM8 12.027c.08 0 .16.018.232.056l3.686 1.894-.694-3.957a.564.564 0 0 1 .163-.505l2.906-2.77-4.052-.576a.525.525 0 0 1-.393-.288L8.002 2.223 8 2.226v9.8z"/>
                            </svg>
                            <svg class="bi bi-star star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                            </svg>
                            <?php
                            break;
                        
                        case $items['status'] >= 3:
                            ?>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                            </svg>
                            <svg class="bi bi-star star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                            </svg>
                            <?php
                            ?>
                        
                            <?php
                            break;
                        
                        default:
                            ?>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-fill star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <svg class="bi bi-star-half star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M5.354 5.119L7.538.792A.516.516 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.537.537 0 0 1 16 6.32a.55.55 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.519.519 0 0 1-.146.05c-.341.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.171-.403.59.59 0 0 1 .084-.302.513.513 0 0 1 .37-.245l4.898-.696zM8 12.027c.08 0 .16.018.232.056l3.686 1.894-.694-3.957a.564.564 0 0 1 .163-.505l2.906-2.77-4.052-.576a.525.525 0 0 1-.393-.288L8.002 2.223 8 2.226v9.8z"/>
                            </svg>
                            <svg class="bi bi-star star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                            </svg>
                            <svg class="bi bi-star star" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M2.866 14.85c-.078.444.36.791.746.593l4.39-2.256 4.389 2.256c.386.198.824-.149.746-.592l-.83-4.73 3.523-3.356c.329-.314.158-.888-.283-.95l-4.898-.696L8.465.792a.513.513 0 0 0-.927 0L5.354 5.12l-4.898.696c-.441.062-.612.636-.283.95l3.523 3.356-.83 4.73zm4.905-2.767l-3.686 1.894.694-3.957a.565.565 0 0 0-.163-.505L1.71 6.745l4.052-.576a.525.525 0 0 0 .393-.288l1.847-3.658 1.846 3.658a.525.525 0 0 0 .393.288l4.052.575-2.906 2.77a.564.564 0 0 0-.163.506l.694 3.957-3.686-1.894a.503.503 0 0 0-.461 0z"/>
                            </svg>
                            <?php
                            break;
                    }
                    ?>
                    <div class="info-counts">
                        <span class="info-count"><?php echo $items['status'];?> Stars</span>
                        <span class="info-count">79 J'adore</span>
                        <span class="info-count">29 Commentaires</span>
                    </div>
                </div>
                <div class="product-price">
                    <span class="prix-price"><?php echo $items['prix'];?> MAD</span>
                    <input type="hidden" name="prix" class="prixprice" value="<?php echo $items['prix'];?>">
                    <span class="prixmax-price"><?php echo $items['prixmax'];?> MAD <hr></span>
                    <input type="hidden" class="prixmaxprice" value="<?php echo $items['prixmax'];?>">
                    <span class="rapport"></span>
                </div>
                <div class="sizes">
                    <div class="title-infoo">
                        <span>Sizes</span>
                        <input name="size" class="sizes-value" type="hidden">
                    </div>
                    <ul>
                        <?php
                        if (!empty($items['size1'])) {
                            ?>
                            <li class="size">
                                <div class="size-nom">
                                    <span ><?php echo $items['size1'];?></span>
                                    <input class="size-1" type="hidden" value="<?php echo $items['size1'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['size'])) {
                            ?>
                            <li class="size2">
                                <div class="size-nom">
                                    <span ><?php echo $items['size2'];?></span>
                                    <input class="size-1" type="hidden" value="<?php echo $items['size2'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['size3'])) {
                            ?>
                            <li class="size">
                                <div class="size-nom">
                                    <span ><?php echo $items['size3'];?></span>
                                    <input class="size-1" type="hidden" value="<?php echo $items['size3'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['size4'])) {
                            ?>
                            <li class="size">
                                <div class="size-nom">
                                    <span ><?php echo $items['size4'];?></span>
                                    <input class="size-1" type="hidden" value="<?php echo $items['size4'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['size5'])) {
                            ?>
                            <li class="size">
                                <div class="size-nom">
                                    <span ><?php echo $items['size5'];?></span>
                                    <input class="size-1" type="hidden" value="<?php echo $items['size5'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['size6'])) {
                            ?>
                            <li class="size">
                                <div class="size-nom">
                                    <span ><?php echo $items['size6'];?></span>
                                    <input class="size-1" type="hidden" value="<?php echo $items['size6'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['size7'])) {
                            ?>
                            <li class="size">
                                <div class="size-nom">
                                    <span ><?php echo $items['size7'];?></span>
                                    <input class="size-1" type="hidden" value="<?php echo $items['size7'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['size8'])) {
                            ?>
                            <li class="size">
                                <div class="size-nom">
                                    <span ><?php echo $items['size8'];?></span>
                                    <input class="size-1" type="hidden" value="<?php echo $items['size8'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['size9'])) {
                            ?>
                            <li class="size">
                                <div class="size-nom">
                                    <span ><?php echo $items['size9'];?></span>
                                    <input class="size-1" type="hidden" value="<?php echo $items['size9'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['size10'])) {
                            ?>
                            <li class="size">
                                <div class="size-nom">
                                    <span ><?php echo $items['size10'];?></span>
                                    <input class="size-1" type="hidden" value="<?php echo $items['size10'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="colors">
                    <div class="title-infoo">
                        <span>Colors</span>
                        <input name="color" class="colors-value" type="hidden">
                    </div>
                    <ul>
                        <?php
                        if (!empty($items['color1'])) {
                            ?>
                            <li class="color">
                                <div class="color-nom">
                                    <span ><?php echo $items['color1'];?></span>
                                    <input class="color-1" type="hidden" value="<?php echo $items['color1'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['color2'])) {
                            ?>
                            <li class="color">
                                <div class="color-nom">
                                    <span ><?php echo $items['color2'];?></span>
                                    <input class="color-1" type="hidden" value="<?php echo $items['color2'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['color3'])) {
                            ?>
                            <li class="color">
                                <div class="color-nom">
                                    <span ><?php echo $items['color3'];?></span>
                                    <input class="color-1" type="hidden" value="<?php echo $items['color3'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['color4'])) {
                            ?>
                            <li class="color">
                                <div class="color-nom">
                                    <span ><?php echo $items['color4'];?></span>
                                    <input class="color-1" type="hidden" value="<?php echo $items['color4'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['color5'])) {
                            ?>
                            <li class="color">
                                <div class="color-nom">
                                    <span ><?php echo $items['color5'];?></span>
                                    <input class="color-1" type="hidden" value="<?php echo $items['color5'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['color6'])) {
                            ?>
                            <li class="color">
                                <div class="color-nom">
                                    <span ><?php echo $items['color6'];?></span>
                                    <input class="color-1" type="hidden" value="<?php echo $items['color6'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['color7'])) {
                            ?>
                            <li class="color">
                                <div class="color-nom">
                                    <span ><?php echo $items['color7'];?></span>
                                    <input class="color-1" type="hidden" value="<?php echo $items['color7'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['color8'])) {
                            ?>
                            <li class="color">
                                <div class="color-nom">
                                    <span ><?php echo $items['color8'];?></span>
                                    <input class="color-1" type="hidden" value="<?php echo $items['color8'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['color9'])) {
                            ?>
                            <li class="color">
                                <div class="color-nom">
                                    <span ><?php echo $items['color9'];?></span>
                                    <input class="color-1" type="hidden" value="<?php echo $items['color9'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        if (!empty($items['color10'])) {
                            ?>
                            <li class="color">
                                <div class="color-nom">
                                    <span ><?php echo $items['color10'];?></span>
                                    <input class="color-1" type="hidden" value="<?php echo $items['color10'];?>">
                                </div>
                            </li>
                            <?php
                        }
                        ?>
                    </ul>
                </div>
                <div class="quantity">
                    <div class="title-infoo">
                        <span>Quantité</span>
                    </div>
                    <button class="btn-quantity moin" type="button">
                        <svg class="bi bi-dash" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M3.5 8a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 0 1H4a.5.5 0 0 1-.5-.5z"/>
                        </svg>
                    </button>
                    <input type="hidden" name="quantite" class="input-quantity" value="1">
                    <span class="number-quantity">1</span>
                    <button class="btn-quantity plus" type="button">
                        <svg class="bi bi-plus" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M8 3.5a.5.5 0 0 1 .5.5v4a.5.5 0 0 1-.5.5H4a.5.5 0 0 1 0-1h3.5V4a.5.5 0 0 1 .5-.5z"/>
                            <path fill-rule="evenodd" d="M7.5 8a.5.5 0 0 1 .5-.5h4a.5.5 0 0 1 0 1H8.5V12a.5.5 0 0 1-1 0V8z"/>
                        </svg>
                    </button>
                </div>
                <div class="div-flex">
                    <div class="submit-item">
                        <button class="smt-item" name="panier" type="submit" disabled>Ajouter au panier</button>
                    </div>
                    <?php
                    if (isset($_SESSION['id'])) {
                        $like_utilisateur = $_SESSION['id'];
                        $stmt=$cnx -> prepare("SELECT * FROM likes WHERE like_produit =? AND like_utilisateur =? AND liked=1");
                        $stmt -> execute(array($itemid,$like_utilisateur));
                        $items =$stmt->fetch();
                        $count = $stmt -> rowCount();
                        if ($count > 0){
                            ?>
                            <div class="like-item liked-item">
                                <input type="hidden" name="likevalue" class="like-value" value="1"> 
                                <button type="submit" name="like" class="submit-like">
                                    <svg class="bi bi-heart no-like lok-like" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                    </svg>
                                    <svg class="bi bi-heart-fill yes-like" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                    </svg>
                                </button>
                            </div>
                            <?php
                        }else {
                            ?>
                            <div class="like-item">
                                <input type="hidden" name="likevalue" class="like-value" value="1"> 
                                <button type="submit" name="like" class="submit-like">
                                    <svg class="bi bi-heart no-like" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                    </svg>
                                    <svg class="bi bi-heart-fill yes-like lok-like" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                    </svg>
                                </button>
                            </div>
                            <?php
                        }
                    }else {
                        ?>
                        <div class="like-item">
                            <input type="hidden" name="likevalue" class="like-value" value="1"> 
                            <button type="submit" name="like" class="submit-like">
                                <svg class="bi bi-heart no-like" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                </svg>
                                <svg class="bi bi-heart-fill yes-like lok-like" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                </svg>
                            </button>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </form>
<?php
}else{
    header("Location: erreur.php");
    exit();
}
?>
<!--  -->
<?php
include $tpl.'maxfooter.php';
include $tpl.'Footer.php';
ob_end_flush();
?>