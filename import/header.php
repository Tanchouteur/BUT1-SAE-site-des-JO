<?php

session_start();


?>

<header>
<nav class="navbar">
    <div class="container-nav">
        <div class="brand">
            <h2 class="navbar">Paris 2024</h2>
        </div>

        <div class="nav-links">
            <a href="#">Accueil</a>
            <a href="src/PHP/Event/gestionEvent">Événements</a>
        </div>

        <div class="auth-buttons">
            <?php

            if (!isset($_SESSION['email'])) {
            echo '<a href="src/html/singin.php" class="btn-login">Connexion</a>
                    <a href="src/html/singup.html" class="btn-signup">Inscription</a>';
            }elseif(isset($_SESSION['email'])){
                echo "<a href='src/PHP/pagePerso.php' class='btn-login'>Profile</a>
                        <a href='src/PHP/deconnect.php' class='btn-signup'>Deconnexion</a>";
            }



            ?>


        </div>
    </div>
</nav>
</header>


