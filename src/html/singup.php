<?php
require_once '../../import/BDD.php';
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur : token CSRF invalide");
    }
    $user = $_POST['username'];
    $user = trim($user);
    $user = filter_var($user, FILTER_SANITIZE_STRING);

    if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $email = strtolower($_POST['email']);
    }else{
        header("location:singup.php?status=0&msg=Entrer une addresse email valide");
    }

    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);

    $sql = "INSERT INTO Users (login, email, mdp, idRole) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($sql);
    $stmt->bind_param("sssi", $user, $email, $pass, $role);

    $sql2 = "select * from Users where email=?";
    $stmt2 = $db->prepare($sql2);
    $stmt2->bind_param("s", $email);
    $stmt2->execute();
    $result = $stmt2->get_result();
    $row = $result->fetch_assoc();

    if (!isset($row["email"])) {

        $sql2 = "select * from AuthorizedEmail where email=?";
        $stmt2 = $db->prepare($sql2);
        $stmt2->bind_param("s", $email);
        $stmt2->execute();
        $result = $stmt2->get_result();
        $row = $result->fetch_assoc();

        if (isset($row["email"])) {
            $role = $row['codeRole'];
        }else{
            $role = 0;
        }

        if ($stmt->execute()) {
            header("location:singin.php?status=1&msg=Inscription Reussi&email=".$email);
        } else {
            header("location:singup.php?status=0&msg=Une erreur est survenue");
        }
    } else {
        header("location:singup.php?status=0&msg=Email deja existant");
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P2024 - Inscription</title>
    <link rel="stylesheet" href="../css/formulaire.css">
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="icon" href="../img/favicon.png" type="image/png">
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

                <a class='btn-navBar' href="singin.php">Se connecter</a>

            </div>
        </div>
    </nav>
</header>
<body>
<div class="formulaire">
    <div class="form-container"><?php if (isset($_GET['status'])&&isset($_GET['msg'])) {
            if ($_GET['status']==0) {
                echo "<h1 style='color: #6c2401'> " . htmlspecialchars($_GET['msg']) . "</h1>";
            }else if ($_GET['status']==1) {
                echo "<h1 style='color: #016c23'> " . htmlspecialchars($_GET['msg']) . "</h1>";
            }
        }?>
        <h2>Inscription</h2>
        <form action="singup.php" method="post">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
            <div class="form-group">
                <label for="username">Nom</label>
                <input type="text" id="username" name="username" placeholder="Utiliser pour la connexion" required>
            </div>
            <div class="form-group">
                <label for="email">Email :</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe :</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit">S'inscrire</button>
        </form>
    </div>
</div>
</body>
</html>
