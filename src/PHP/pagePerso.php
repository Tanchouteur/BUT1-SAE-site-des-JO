<?php session_start();

require_once "../../import/BDD.php";

$email = $_SESSION['email'];
$status = "";
if (!empty($_POST)) {
    $new_email = $_POST["email"];
    $new_pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $new_nom = $_POST["nom"];
    $new_age = $_POST["age"];
    $status = "Le formulaire a été soumis.";
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

$sql = "SELECT * FROM Users WHERE email = ?";
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
    <title>Jeux Olympiques 2024</title>
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/header.css">
</head>

<header>
    <nav class="navbar">
        <div class="container-nav">
            <div class="brand">
                <h2 class="navbar">Paris 2024</h2>
            </div>

            <div class="nav-links">
                <a href="../../index.php">Accueil</a>
                <a href="../../index.php#">Événements</a>
            </div>

            <div class="auth-buttons">

                <?php echo "<a href='deconnect.php' class='btn-signup'>Deconnexion</a>"; ?>

            </div>
        </div>
    </nav>
</header>
<body>
<h2><?php echo $status;?></h2>
<form action="pagePerso.php" method="post">

    <div class="form-group">
        <label for="email">Email :</label>
        <input type="email" id="email" name="email" value="<?php echo $result['email'] ?>" required>
    </div>
    <div class="form-group">
        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password">
    </div>
    <div>
        <label for="nom">Nom : </label>
        <input type="text" id="nom" name="nom" value="<?php echo $result['login'] ?>" required>
    </div>

    <div>
        <label for="nom">Role : <?php echo $result['idRole'] ?></label>
    </div>

    <div>
        <label for="nom">Age : </label>
        <input type="number" id="age" name="age" value="<?php echo $result['age'] ?>" >
    </div>


    <button type="submit">S'inscrire</button>
</form>

</body>
</html>
