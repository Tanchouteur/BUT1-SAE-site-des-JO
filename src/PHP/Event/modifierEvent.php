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

    $sql = "UPDATE Event 
            SET nomEvent = ?, lieuEvent = ?, descriptionEvent = ?, typeEvent = ?, roleEvent = ?, dateEvent = ? 
            WHERE nomEvent = ?";
    $stmt = $db->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssisss", $nomEvent, $lieuEvent, $descriptionEvent, $typeEvent, $roleEvent, $dateEvent,$nomEvent);
        if ($stmt->execute()) {
            $status = "Événement modifier avec succès!";
        } else {
            $status = "Erreur lors de l'exécution de la requête : " . $stmt->error;
        }
        $stmt->close();
    } else {
        $status = "Erreur lors de la préparation de la requête : " . $db->error;
    }
}

$sql2 = "SELECT * FROM Event where nomEvent = ?";
$stmt = $db->prepare($sql2);
$stmt->bind_param("s", $_GET['event']);
$stmt->execute();
$result = $stmt->get_result();
$result = $result->fetch_assoc();
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

<form action="modifierEvent.php?event=<?php echo $_GET['event']?>" method="post">

    <div class="form-group">
        <label for="nomEvent">Nom de l'évenement : </label>
        <input type="text" id="nomEvent" name="nomEvent" value="<?php echo $result['nomEvent'] ?>" required>
    </div>
    <div class="form-group">
        <label for="lieuEvent">Lieux de l'évenement :</label>
        <input type="text" id="lieuEvent" name="lieuEvent" value="<?php echo $result['lieuEvent'] ?>" required>
    </div>
    <div>
        <label for="descriptionEvent">Description : </label>
        <input type="text" id="descriptionEvent" name="descriptionEvent"  value="<?php echo $result['descriptionEvent'] ?>" required>
    </div>

    <div>
        <label for="typeEvent">Type d'évenement : </label>
        <select id="typeEvent" name="typeEvent" required >

            <?php

            if ($result['typeEvent'] == 1) {
                echo "<option value='1' selected>Type 1</option>
            <option value='2'>Type 2</option>
            <option value='3'>Type 3</option>";
            }elseif ($result['typeEvent'] == 2) {
                echo "<option value='1' >Type 1</option>
            <option value='2' selected>Type 2</option>
            <option value='3'>Type 3</option>";
            }elseif ($result['typeEvent'] == 3) {
                echo "<option value='1'>Type 1</option>
            <option value='2'>Type 2</option>
            <option value='3' selected>Type 3</option>";
            }

            ?>


        </select>
    </div>

    <div>
        <label for="roleEvent">Type d'évenement : </label>
        <?php
        if ($result['roleEvent'] == 1) {
            echo "<div>
            <input type='checkbox' id='spectateur' name='spectateur' value='0' checked/>
            <label for='spectateur'>Spectateur</label>
        </div>

        <div>
            <input type='checkbox' id='sportif' name='sportif' value='1'/>
            <label for='sportif'>Sportif</label>
        </div>";
        }elseif ($result['roleEvent'] == 2) {
            echo "<div>
            <input type='checkbox' id='spectateur' name='spectateur' value='0' />
            <label for='spectateur'>Spectateur</label>
        </div>

        <div>
            <input type='checkbox' id='sportif' name='sportif' value='1' checked/>
            <label for='sportif'>Sportif</label>
        </div>";
        }elseif ($result['roleEvent'] == 3) {
            echo "<div>
            <input type='checkbox' id='spectateur' name='spectateur' value='0' checked/>
            <label for='spectateur'>Spectateur</label>
        </div>

        <div>
            <input type='checkbox' id='sportif' name='sportif' value='1' checked/>
            <label for='sportif'>Sportif</label>
        </div>";
        }        ?>

    </div>
    <div>
        <label for="dateEvent">Date de l'évenement : </label>
        <input type="date" id="dateEvent" name="dateEvent" value="<?php echo $result['nomEvent'] ?>" required/>
    </div>


    <button type="submit">Modifier l'évenement</button>
</form>
<?php if (!empty($status)) { echo "<p>$status</p>"; } ?>
</body>
</html>
