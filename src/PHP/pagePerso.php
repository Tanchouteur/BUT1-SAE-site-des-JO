<?php session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

require_once "../../import/BDD.php";

$email = $_SESSION['email'];
$status = "";
if (!empty($_POST)) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur : token CSRF invalide");
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $new_email = strtolower($_POST['email']);
    }else{
        $status = "Entrée une email valide";
    }

    $new_pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $new_nom = $_POST["nom"];
    $new_nom = trim($new_nom); // Supprimer les espaces en début et fin
    $new_nom = htmlspecialchars($new_nom, ENT_QUOTES, 'UTF-8');

    if (strlen($new_nom) > 50) {
        $new_nom = substr($new_nom, 0, 50);
    }

    if(filter_var($_POST["age"], FILTER_VALIDATE_INT) !== false) {
        $new_age = $_POST["age"];
    }

    $status = "Données Mise à Jour";
    if (isset($_POST['password'])) {
        $sql = "UPDATE Users SET login = ?, email = ?, mdp = ?, age = ? WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("sssis", $new_nom, $new_email, $new_pass, $new_age, $email);
    }else{
        $sql = "UPDATE Users SET login = ?, email = ?, age = ? WHERE email = ?";
        $stmt = $db->prepare($sql);
        $stmt->bind_param("ssis", $new_nom, $new_email, $new_age, $email);

    }
    $stmt->execute();
}

$sql = "SELECT * FROM Users NATURAL JOIN Roles WHERE email = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();
$result = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P2024 - Profile</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header.css">
    <link rel="stylesheet" href="../css/formulaire.css">
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

                <?php echo "<a class='btn-navBar' href='deconnect.php' class='btn-signup'>Deconnexion</a>"; ?>

            </div>
        </div>
    </nav>
</header>
<body>

<h2 class="formH2"><?php echo $status;?></h2>

<div class="formulaire">

    <form action="pagePerso.php" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
        <div class="form-group">
            <label for="email">Email :</label>
            <input type="email" id="email" name="email" value="<?php echo $result['email'] ?>" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe :</label>
            <input type="password" id="password" name="password">
        </div>
        <div class="form-group">
            <label for="nom">Nom : </label>
            <input type="text" id="nom" name="nom" value="<?php echo $result['login'] ?>" required>
        </div>

        <div class="form-group">
            <label for="nom">Role : <?php echo $result['nomRole'] ?></label>
        </div>

        <div class="form-group">
            <label for="nom">Age : </label>
            <input type="number" id="age" name="age" value="<?php echo $result['age'] ?>" >
        </div>


        <button type="submit">Sauvegarder</button>

    </form>

</div>
<div class="form-group-delete">
    <a class="delete" href="supprCompte.php">Supprimer le Compte</a>
</div>
</body>
</html>
