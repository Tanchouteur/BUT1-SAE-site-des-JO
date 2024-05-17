<?php
require_once "import/BDD.php";

$event = 0;
if (isset($_SESSION['email'])) {

    $email = $_SESSION['email'];

    $sql = "SELECT idRole FROM Users where email = '$email'";
    $result = mysqli_query($db, $sql);
    $result = mysqli_fetch_array($result, MYSQLI_ASSOC);

    if ($result['idRole'] == 2) {
        $event = 1;
    }
}

?>

<header>
<nav class="navbar">
    <div class="container-nav">
        <div class="brand">
            <h2 class="navbar">Paris 2024</h2>
        </div>

        <div class="nav-links">
            <a href="#">Accueil</a>
            <?php
            if ($event==1){
                echo "<a href='src/PHP/Event/gestionEvent.php'>Événements</a>";
            }            ?>
        </div>

        <div class="auth-buttons">
            <?php

            if (!isset($_SESSION['email'])) {
            echo '<a href="src/html/singin.php" class="btn-login">Connexion</a>
                    <a href="src/html/singup.php" class="btn-signup">Inscription</a>';
            }elseif(isset($_SESSION['email'])){
                echo "<a href='src/PHP/pagePerso.php' class='btn-login'>Profile</a>
                        <a href='src/PHP/deconnect.php' class='btn-signup'>Deconnexion</a>";
            }



            ?>


        </div>
    </div>
</nav>
</header>


