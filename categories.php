
<?php 
ob_start();
session_start();
$pageTitle='Categories';
include 'init.php';
?>

<?php
if ($_SERVER['REQUEST_METHOD']=='POST') {
    if (isset($_SESSION['id'])){

        $likevalue = $_POST['likevalue'];
        $like_utilisateur = $_SESSION['id'];
        $like_produit = $_POST['produitid'];

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
    }else{
        header("Location: connecter.php");
        exit();
    }
}

$catid = isset($_GET['index']) && is_numeric($_GET['index']) ? intval($_GET['index']) :0;

$stmt=$cnx -> prepare("SELECT * FROM produits WHERE produit_categorie=? AND active=1");
$stmt -> execute(array($catid));
$items =$stmt->fetchAll();
$count = $stmt -> rowCount();
if ($count > 0){
$cats = getAllFrom('*', 'categories', 'WHERE active=1', '', 'categorie_id');
    foreach ($cats as $cat) {
        switch ($cat['nom']) {
            case 'Femmme':
                ?>
                <div class="templates-cat">
                    <div class="template-cat">
                        <img src="<?php echo $img ?>Cat-10.png" class="cat-img" alt="">
                    </div>
                </div>
                <?php
                break;
            case 'Homme':
                ?>
                <div class="templates-cat">
                    <div class="template-cat">
                        <img src="<?php echo $img ?>Cat-2.png" class="cat-img" alt="">
                    </div>
                </div>
                <?php
                break;
            case 'Homme':
                ?>
                <div class="templates-cat">
                    <div class="template-cat">
                        <img src="Cat-10.png" class="cat-img" alt="">
                    </div>
                </div>
                <?php
                break;
            
            default:
                # code...
                break;
        }
    }
    
    echo '<main class="flex-box">';
    foreach($items as $item){
        ?>
        <div class="cart cart-cat">
            <div class="price-cerner">
                <div class="img-wrapper">
                    <img class="img" src="<?php echo $imgp . $item['image1'];?>" alt="">
                </div>
                <div class="content-wrapper">
                    <p class="title"><a href="items.php?index=<?php echo $item['produit_id'];?>"><?php echo $item['nom'];?></a></p>
                    <div class="price">
                        <span class="status">
                            <svg class="bi bi-star-fill svgstutas" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                            </svg>
                            <span class="statu"><?php echo $item['status'];?></span>
                        </span>
                        <span class="prix"><?php echo $item['prix'];?> MAD</span>
                        <span class="prix prix-max"><?php echo $item['prixmax'];?> MAD <hr></span>
                    </div>
                    <div class="inner-content-wrapper">
                        <div class="icons">
                            <form action="<?php echo $_SERVER['PHP_SELF'] . '?index=' . $catid?>" method="POST">
                                <?php
                                if (isset($_SESSION['id'])) {
                                    $like_utilisateur = $_SESSION['id'];
                                    $stmt=$cnx -> prepare("SELECT * FROM likes WHERE like_produit =? AND like_utilisateur =? AND liked=1");
                                    $stmt -> execute(array($item['produit_id'],$like_utilisateur));
                                    $items =$stmt->fetch();
                                    $count = $stmt -> rowCount();
                                    if ($count > 0){
                                        ?>
                                        <div class="like-plus">
                                            <input type="hidden" name="likevalue" class="like-val" value="1"> 
                                            <input type="hidden" name="produitid" value="<?php echo $item['produit_id'];?>">
                                            <button type="submit" name="like" class="submit-like">
                                                <svg class="bi bi-heart likelike-plus likein likeout" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                </svg>
                                                <svg class="bi bi-heart-fill likelike-plus liked" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <?php
                                    }else {
                                        ?>
                                        <div class="like-plus">
                                            <input type="hidden" name="likevalue" class="like-val" value="1"> 
                                            <input type="hidden" name="produitid" value="<?php echo $item['produit_id'];?>">
                                            <button type="submit" name="like" class="submit-like">
                                                <svg class="bi bi-heart likelike-plus likein" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                                </svg>
                                                <svg class="bi bi-heart-fill likelike-plus liked likeout" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                                </svg>
                                            </button>
                                        </div>
                                        <?php
                                    }
                                }else {
                                    ?>
                                    <div class="like-plus">
                                        <input type="hidden" name="likevalue" class="like-val" value="1"> 
                                        <input type="hidden" name="produitid" value="<?php echo $item['produit_id'];?>">
                                        <button type="submit" name="like" class="submit-like">
                                            <svg class="bi bi-heart likelike-plus likein" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                                            </svg>
                                            <svg class="bi bi-heart-fill likelike-plus liked likeout" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <?php
                                }
                                ?>
                            </form>
                            <a href="items.php?index=<?php echo $item['produit_id'];?>" class="iconcheter">
                                <svg class="bi bi-cart4" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M0 2.5A.5.5 0 0 1 .5 2H2a.5.5 0 0 1 .485.379L2.89 4H14.5a.5.5 0 0 1 .485.621l-1.5 6A.5.5 0 0 1 13 11H4a.5.5 0 0 1-.485-.379L1.61 3H.5a.5.5 0 0 1-.5-.5zM3.14 5l.5 2H5V5H3.14zM6 5v2h2V5H6zm3 0v2h2V5H9zm3 0v2h1.36l.5-2H12zm1.11 3H12v2h.61l.5-2zM11 8H9v2h2V8zM8 8H6v2h2V8zM5 8H3.89l.5 2H5V8zm0 5a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0zm9-1a1 1 0 1 0 0 2 1 1 0 0 0 0-2zm-2 1a2 2 0 1 1 4 0 2 2 0 0 1-4 0z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    echo '</main>';
    
}else{
    header("Location: erreur.php");
    exit();
}
?>
<?php 
include $tpl.'maxfooter.php';
include $tpl.'Footer.php'; 
ob_end_flush();
?>