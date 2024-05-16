<?php

session_start();


?>

<header>
<nav class="navbar">
    <div class="container-nav">
        <div class="brand">
            <a href="#">Paris 2024</a>
        </div>

        <div class="nav-links">
            <a href="#">Accueil</a>
            <a href="#">Événements</a>
        </div>

        <div class="auth-buttons">
            <?php

            if (!isset($_SESSION['email'])) {
            echo '<a href="src/html/singin.php" class="btn-login">Connexion</a>
                    <a href="src/html/singup.html" class="btn-signup">Inscription</a>';
            }elseif(isset($_SESSION['email'])){
                echo "<h2 class='connected'>Bonjour " . $_SESSION['nom'] . "</h2>
                        <a href='src/PHP/deconnect.php' class='btn-signup'>Deconnexion</a>";
            }



            ?>


        </div>
    </div>
</nav>
</header>


