<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    require_once '../../import/BDD.php';
    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = strtolower($_POST['email']);
    }else{
        header("location:singin.php?status=0&msg=Entrer une addresse email valide");
    }

    $pass = $_POST['password'];
    $hashedPass = password_hash($pass, PASSWORD_DEFAULT);


    $sql = "SELECT * FROM Users WHERE email = ?";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        var_dump($row);
        if (password_verify($pass, $row['mdp'])) {

            $_SESSION['email'] = $row['email'];
            $_SESSION['nom'] = $row['login'];
            $_SESSION['idRole'] = $row['idRole'];

            header("location:../../index.php?status=1&msg=succes");

        } else {
            header("location:singin.php?status=0&msg=Login ou mot de passe incorrect");
        }
    } else {
        header("location:singin.php?status=0&msg=Login ou mot de passe incorrect");
    }

}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/formulaire.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header.css">
</head>
<header>
    <nav class="navbar">
        <div class="container-nav">
            <div class="brand">
                <img class="logo" src="../img/Paris2024.png">
            </div>

            <div class="nav-links">
                <a class='btn-navBar' href="../../index.php">Accueil</a>
                <a class='btn-navBar' href="../../index.php#">Liste des Événements</a>
            </div>

            <div class="auth-buttons">

                <a class='btn-navBar' href="singup.php">S'inscrire</a>

            </div>
        </div>
    </nav>
</header>
<body>
<div class="formulaire">
    <div class="form-container"><?php
        if (isset($_GET['status'])&&isset($_GET['msg'])) {
            if ($_GET['status']==0) {
                echo "<h1 style='color: #6c2401'> " . $_GET['msg'] . "</h1>";
            }else if ($_GET['status']==1) {
                echo "<h1 style='color: #016c23'> " . $_GET['msg'] . "</h1>";
            }
        }?>
        <h2>Connexion</h2>
        <form action="singin.php" method="post">

            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" value="<?php if(isset($_GET['email'])){ echo $_GET['email'];} ?>" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">Se Connecter</button>
        </form>
    </div>
</div>
</body>
</html>