<?php
session_start();
require_once "../../../import/BDD.php";

$email = $_SESSION['email'];
$status = "";
if (!empty($_POST)) {
    $nomEvent = $_POST["nomEvent"];
    $lieuEvent = $_POST['lieuEvent']; // Correction ici
    $descriptionEvent = $_POST["descriptionEvent"];
    $typeEvent = $_POST["typeEvent"];
    $createurEvent = $_SESSION["email"];
    $dateEvent = $_POST["dateEvent"];

    // Combinaison des valeurs des cases à cocher
    $roleEvent = 0;
    if (isset($_POST['spectateur'])) {
        $roleEvent += 1;
    }
    if (isset($_POST['sportif'])) {
        $roleEvent += 2;
    }

    $sql = "INSERT INTO Event (nomEvent, lieuEvent, descriptionEvent, typeEvent,roleEvent,createurEvent,dateEvent) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $db->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssiss", $nomEvent, $lieuEvent, $descriptionEvent, $typeEvent, $roleEvent, $createurEvent, $dateEvent);
        if ($stmt->execute()) {
            $status = "Événement créé avec succès!";
        } else {
            $status = "Erreur lors de l'exécution de la requête : " . $stmt->error;
        }
        $stmt->close();
    } else {
        $status = "Erreur lors de la préparation de la requête : " . $db->error;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Connexion</title>
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/styles.css">
</head>
<header>
    <nav class="navbar">
        <div class="container-nav">
            <div class="brand">
                <h2 class="navbar">Paris 2024</h2>
            </div>

            <div class="nav-links">
                <a href="../../../index.php">Accueil</a>
                <h2 class="navbar active">Evenement</h2>
            </div>

            <div class="auth-buttons">

                <?php echo "<a href='deconnect.php' class='btn-signup'>Deconnexion</a>"; ?>

            </div>
        </div>
    </nav>
</header>
<body>
    <div class="container-GestionEvent">
        <a href="gestionEvent.php">Gestion des evenement</a>
    </div>

    <form action="creeEvent.php" method="post">

        <div class="form-group">
            <label for="nomEvent">Nom de l'évenement : </label>
            <input type="text" id="nomEvent" name="nomEvent" required>
        </div>
        <div class="form-group">
            <label for="lieuEvent">Lieux de l'évenement :</label>
            <input type="text" id="lieuEvent" name="lieuEvent" required>
        </div>
        <div>
            <label for="descriptionEvent">Description : </label>
            <input type="text" id="descriptionEvent" name="descriptionEvent" required>
        </div>

        <div>
            <label for="typeEvent">Type d'évenement : </label>
            <select id="typeEvent" name="typeEvent" required >
                <option value="1">Type 1</option>
                <option value="2">Type 2</option>
                <option value="3">Type 3</option>
            </select>
        </div>

        <div>
            <label for="roleEvent">Type d'évenement : </label>
            <div>
                <input type="checkbox" id="spectateur" name="spectateur" value="0"/>
                <label for="spectateur">Spectateur</label>
            </div>

            <div>
                <input type="checkbox" id="sportif" name="sportif" value="1"/>
                <label for="sportif">Sportif</label>
            </div>

        </div>
        <div>
            <label for="dateEvent">Date de l'évenement : </label>
            <input type="date" id="dateEvent" name="dateEvent"/>
        </div>


        <button type="submit">Crée l'évenement</button>
    </form>
    <?php if (!empty($status)) { echo "<p>$status</p>"; } ?>
</body>
</html>
