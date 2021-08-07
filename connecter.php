<?php 
session_start();
$nonavbar='';
$pageTitle='Connecter';
// if (isset($_SESSION['id'])) {
//     header('Location: index.php');
// }
include 'init.php'; 

if ($_SERVER['REQUEST_METHOD']=='POST') {
    if(isset($_POST['login'])){
        $email = $_POST['email'];
        $pass = $_POST['password'];
        
        $stmt=$cnx -> prepare("SELECT utilisateur_id, email, motdepasse 
        FROM utilisateur WHERE email=? AND motdepasse=?");
        $stmt -> execute(array($email,$pass));
        $get = $stmt -> fetch();
        $count = $stmt -> rowCount();
        if ($count>0) {
            $_SESSION['id'] = $get['utilisateur_id'];
            header('Location: index.php');
            exit();
        }else {
            ?>
            <script>
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'L’adresse e-mail ou le mot de passe que vous avez entré n’est pas valide !',
                })
            </script>
            <?php 
        }
    }elseif(isset($_POST['insription'])){
        $formErrors = array();
        $nom = filter_var($_POST['nom'], FILTER_SANITIZE_STRING);
        $prenom = filter_var($_POST['prenom'], FILTER_SANITIZE_STRING);
        $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $adress = filter_var($_POST['adress'], FILTER_SANITIZE_STRING);
        $motdepass1 = filter_var($_POST['password1'], FILTER_SANITIZE_STRING);
        $motdepass2 = filter_var($_POST['password2'], FILTER_SANITIZE_STRING);

        $chik=check("email", "utilisateur",$email);
        if ($chik==1) { 
            ?>
            <script>
                Swal.fire({
                icon: 'error',
                title: 'Oops...',
                text: 'Cette adresse e-mail est déjà utilisée !',
                })
            </script>
            <?php 
        }
        else {
            $stmt=$cnx->prepare("INSERT INTO  
            utilisateur(nom, prenom, email, motdepasse, adress, date) 
            VALUES (:znom, :zprenom, :zemail, :zmotdepass, :zadress, now())");
            $stmt->execute(array( 
            'znom'=>$nom, 
            'zprenom'=>$prenom, 
            'zemail'=>$email,
            'zmotdepass'=>$motdepass1,
            'zadress'=>$adress,
            ));

            if ($stmt) {
                ?>
                <script>
                    const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    onOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                    })

                    Toast.fire({
                    icon: 'success',
                    title: 'Vous êtes utilisateur'
                    })
                </script>
                <?php 
            }
        }
    }
}
?>

<div class="content">
    <div class="logo-in">
        <header>Bienvenue</header>
        <img src="<?php echo $img;?>logo_in.svg" alt="">
    </div>
    <div class="forminfo">
        <div class="button-box">
            <div id="btn" class="btnlok"></div>
            <button class="toggle-btn btnlogin" id="btnlogin">Connecter</button>
            <button class="toggle-btn btninsr" id="btninsr">Inscription</button>
        </div>
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" id="login">
            <div class="form">
                <div class="row100">
                    <div class="col">
                        <div class="inputBox">
                            <svg class="bi bi-envelope info" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M14 3H2a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4a1 1 0 0 0-1-1zM2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V4a2 2 0 0 0-2-2H2z"/>
                                <path d="M.05 3.555C.017 3.698 0 3.847 0 4v.697l5.803 3.546L0 11.801V12c0 .306.069.596.192.856l6.57-4.027L8 9.586l1.239-.757 6.57 4.027c.122-.26.191-.55.191-.856v-.2l-5.803-3.557L16 4.697V4c0-.153-.017-.302-.05-.445L8 8.414.05 3.555z"/>
                                </svg>
                            <input type="email" name="email" required id="emaill">
                            <span class="text">Email</span>
                        </div>
                    </div>
                </div>
                <div class="row100">
                    <div class="col">
                        <div class="inputBox">
                            <svg class="bi bi-lock info" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M11.5 8h-7a1 1 0 0 0-1 1v5a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V9a1 1 0 0 0-1-1zm-7-1a2 2 0 0 0-2 2v5a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-7zm0-3a3.5 3.5 0 1 1 7 0v3h-1V4a2.5 2.5 0 0 0-5 0v3h-1V4z"/>
                                </svg>
                            <input type="password" name="password" required id="password">
                            <span class="text">Mot de pass</span>
                        </div>
                    </div>
                </div>
                <div class="row100">
                    <div class="col">
                        <input type="submit" name="login" value="Se connecter" class="liko" id="log" disabled>
                    </div>
                </div>
            </div>
        </form>
        <!--  -->
        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST" id="insription">
            <div class="form">
                <div class="row100">
                    <div class="col">
                        <div class="inputBox">
                            <input type="text" name="nom" id="nom" required>
                            <span class="text">Nom</span>
                        </div>
                    </div>
                    <div class="col">
                        <div class="inputBox">
                            <input type="text" name="prenom" id="prenom" required>
                            <span class="text">Prenom</span>
                        </div>
                    </div>
                </div>
                <div class="row100">
                    <div class="col">
                        <div class="inputBox">
                            <input type="email" name="email" id="email" required>
                            <span class="text">Email</span>
                        </div>
                    </div>
                </div>
                <div class="row100">
                    <div class="col">
                        <div class="inputBox">
                            <input type="text" name="adress" id="adress" required>
                            <span class="text">Adress</span>
                        </div>
                    </div>
                </div>
                <div class="row100">
                    <div class="col">
                        <div class="inputBox">
                            <input type="password" name="password1" required id="pass1" pattern=".{8,}" title="Utilisez 8 caractères ou plus pour votre mot de passe">
                            <span class="text">Mot de pass</span>
                            <svg class="bi bi-eye-slash eye eyeclose" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7.028 7.028 0 0 0-2.79.588l.77.771A5.944 5.944 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.134 13.134 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755-.165.165-.337.328-.517.486l.708.709z"/>
                                <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829l.822.822zm-2.943 1.299l.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829z"/>
                                <path d="M3.35 5.47c-.18.16-.353.322-.518.487A13.134 13.134 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7.029 7.029 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709z"/>
                                <path fill-rule="evenodd" d="M13.646 14.354l-12-12 .708-.708 12 12-.708.708z"/>
                            </svg>
                            <svg class="bi bi-eye eye eyeopen" width="1em" height="1em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.134 13.134 0 0 0 1.66 2.043C4.12 11.332 5.88 12.5 8 12.5c2.12 0 3.879-1.168 5.168-2.457A13.134 13.134 0 0 0 14.828 8a13.133 13.133 0 0 0-1.66-2.043C11.879 4.668 10.119 3.5 8 3.5c-2.12 0-3.879 1.168-5.168 2.457A13.133 13.133 0 0 0 1.172 8z"/>
                                <path fill-rule="evenodd" d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="col">
                        <div class="inputBox">
                            <input type="password" name="password2" required id="pass2">
                            <span class="text">Confirmer</span>
                            <span class="erreur" id="erreur"></span>
                        </div>
                    </div>
                </div>
                <div class="row100">
                    <div class="col">
                        <input type="submit" name="insription" value="Inscription" id="inscr" class="liko" disabled>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php 
include $tpl.'Footer.php'; 
?>