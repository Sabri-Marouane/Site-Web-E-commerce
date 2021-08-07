<?php
ob_start();
session_start();
$pageTitle='Accueil';
include 'init.php';
?>
<!--  -->
<div class="templates">
    <div class="template-grand">
        <div id="carouselExampleFade" class="carousel slide carousel-fade" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img src="<?php echo $img;?>by-like-11.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>by-like-1.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>by-like-2.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>by-like-3.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>by-like-4.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>by-like-5.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>by-like-6.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>by-like-7.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>by-like-8.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>by-like-9.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>by-like-10.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>by-like-1.gif" class="d-block w-100 template-img" alt="...">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleFade" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleFade" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            </div>
    </div>
    <div class="template-petite">
        <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="3"></li>
                <li data-target="#carouselExampleIndicators" data-slide-to="4"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                <img src="<?php echo $img;?>Petite-1.jpg" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>Petite-2.jpg" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>Petite-3.jpg" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>Petite-4.png" class="d-block w-100 template-img" alt="...">
                </div>
                <div class="carousel-item">
                <img src="<?php echo $img;?>Petite-5.jpg" class="d-block w-100 template-img" alt="...">
                </div>
            </div>
            <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
            </div>
    </div>
</div>
<!--  -->
<div class="classements">
    <img src="<?php echo $img;?>marcu.png" alt="" class="classement">
</div>
<!--  -->
<?php
// $stmtcat=$cnx -> prepare("SELECT * FROM categories");
// $stmtcat -> execute();
// $items = $stmtcat->fetch();
$sliderproduit = getAllFrom('*', 'categories', 'WHERE active=1', '', 'categorie_id');
foreach($sliderproduit as $item){
  ?>
  <div class="list-choix">
        <div class="list-produit">
            <div class="list-info">
                <a href="categories.php?index=<?php echo $item['categorie_id'];?>"><span class="list-nom-cat"><?php echo $item['nom'];?></span></a>
                <a href="categories.php?index=<?php echo $item['categorie_id'];?>">
                  <span class="list-voir">
                    <span>Voir plus</span>
                    <span class="list-icon">
                      <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-chevron-right" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708z"/>
                      </svg>
                    </span>
                  </span>
                </a>
            </div>
            <!--  -->
            <div class="carousel" data-flickity='{ "contain": true, "groupCells": true }'>
            <?php 
            // $stmt=$cnx -> prepare("SELECT * FROM produits WHERE active=1");
            // $stmt -> execute();
            // $produits =$stmt->fetch();
            $produits = getAllFrom('*', 'produits', 'WHERE active=1', '', 'produit_id');
            foreach($produits as $produit){
                ?>
                <a href="items.php?index=<?php echo $produit['produit_id'];?>">
                    <div class="">
                        <div class="cart cart-slider">
                            <div class="price-cerner">
                                <div class="img-wrapper">
                                    <img class="img img-slider" src="<?php echo $imgp . $produit['image1'];?>" alt="">
                                </div>
                                <div class="content-wrapper">
                                    <p class="title"><a href="items.php?index=<?php echo $produit['produit_id'];?>"><?php echo $produit['nom'];?></a></p>
                                    <div class="price">
                                        <span class="status">
                                            <svg class="bi bi-star-fill svgstutas" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.283.95l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                                            </svg>
                                            <span class="statu"><?php echo $produit['status'];?></span>
                                        </span>
                                        <span class="prix"><?php echo $produit['prix'];?> MAD</span>
                                        <span class="prix prix-max"><?php echo $produit['prixmax'];?> MAD <hr></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
                <?php 
            }
            ?>
            </div>
      </div>
  </div>
  <?php
}
?>
<?php
include $tpl.'maxfooter.php';
include $tpl.'Footer.php';
ob_end_flush();
?>