<?php
session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

if (!isset($_GET['event']) || $_GET['event'] == ""){
    header('location: ../../../index.php');
    exit();
} else {
    $event = $_GET['event'];
}

if (isset($_SESSION['email']) && isset($_SESSION['idRole']) && isset($_SESSION['nom'])) {
    $email = $_SESSION['email'];
    $idRole = $_SESSION['idRole'];
    $nom = $_SESSION['nom'];
}

require_once "../../../import/BDD.php";

$sql = "SELECT nomEvent, lieuEvent, descriptionEvent, typeEvent, roleEvent, createurEvent, dateEvent FROM Event WHERE nomEvent = ?";
$stmt = $db->prepare($sql);
if ($stmt) {
    $stmt->bind_param("s", $event);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    if (!$result) {
        header('location: ../../../index.php');
        exit();
    }
} else {
    echo "Erreur de preparation : " . $db->error;
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Erreur : token CSRF invalide");
    }
    if (isset($_POST['commentaire'])) {

        $new_commentaire = $_POST['commentaire'];
        $new_commentaire = trim($new_commentaire);
        $new_commentaire = htmlspecialchars($new_commentaire, ENT_QUOTES, 'UTF-8');

        if (strlen($new_commentaire) > 254) {
            $new_commentaire = substr($new_commentaire, 0, 254);
        }

        $sql = "INSERT INTO Commentaire (nom, email, idRole, event, commentaire) VALUES (?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->bind_param('ssiss', $nom, $email, $idRole, $event, $new_commentaire);
        if ($stmt->execute()) {
            $status = 1;
        } else {
            $status = 0;
        }

        $stmt->close();
    }
}

$commentaire = "SELECT * FROM Commentaire WHERE event = ?";
$stmt = $db->prepare($commentaire);
$stmt->bind_param("s", $event);
$stmt->execute();
$resultCommentaire = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>P2024 - Page de l'Evenement</title>
    <link rel="stylesheet" href="../../css/header.css">
    <link rel="stylesheet" href="../../css/styles.css">
    <link rel="stylesheet" href="../../css/formulaire.css">
    <link rel="icon" href="../../img/favicon.png" type="image/png">
</head>
<header>
    <nav class="navbar">
        <div class="container-nav">
            <div class="brand">
                <img class="logo" src="../../img/Paris2024.png">
            </div>

            <div class="nav-links">
                <a class='btn-navBar' href="../../../index.php">Accueil</a>
            </div>

            <div class="auth-buttons">
                <?php
                if (isset($_SESSION['email']) && isset($_SESSION['idRole']) && isset($_SESSION['nom'])){
                    echo "<a class='btn-navBar' href='../deconnect.php' class='btn-signup'>Deconnexion</a>";
                }else{
                    echo "<a class='btn-navBar' href='../../html/singin.php' class='btn-signup'>Connexion</a>";
                    echo "<a class='btn-navBar' href='../../html/singup.php' class='btn-signup'>Inscription</a>";
                }
                ?>
            </div>
        </div>
    </nav>
</header>
<body>
<div class="GestionPageEvent EventPresentation">
    <h2>Titre de l'évenement :  <?php echo isset($result['nomEvent']) ? htmlspecialchars($result['nomEvent']) : 'N/A'; ?></h2>
    <h3>Lieux de l'évenement :  <?php echo isset($result['lieuEvent']) ? htmlspecialchars($result['lieuEvent']) : 'N/A'; ?></h3>
    <h4>Type de l'évenement : <?php echo isset($result['typeEvent']) ? htmlspecialchars($result['typeEvent']) : 'N/A'; ?> - </h4>
    <h4>Les role de l'évenement :  <?php
        if (isset($result['roleEvent'])) {
            if ($result['roleEvent'] == 1) {
                echo "Spectateur";
            } elseif ($result['roleEvent'] == 2) {
                echo "Sportif";
            } elseif ($result['roleEvent'] == 3) {
                echo "Spectateur et sportif";
            }
        } else {
            echo 'N/A';
        }
        ?></h4>
    <h4>Description de l'évenement :  <?php echo isset($result['descriptionEvent']) ? htmlspecialchars($result['descriptionEvent']) : 'N/A'; ?></h4>
    <h4>Créateur de l'évenement : <?php echo isset($result['createurEvent']) ? htmlspecialchars($result['createurEvent']) : 'N/A'; ?></h4>
    <h4>Date de l'évenement : <?php echo isset($result['dateEvent']) ? htmlspecialchars($result['dateEvent']) : 'N/A'; ?></h4>
</div>

<div class="GestionCom">
    <div class="sectionCom">
        <?php
        if (!empty($resultCommentaire)) {
            foreach ($resultCommentaire as $commentaire) {
                $nomRole = 'N/A';
                if ($commentaire['idRole'] == 0) {
                    $nomRole = 'Spectateur';
                } elseif ($commentaire['idRole'] == 1) {
                    $nomRole = 'Sportif';
                } elseif ($commentaire['idRole'] == 2) {
                    $nomRole = "Organisateur";
                }

                echo "<div class='";
                if ($commentaire['nom'] == $nom) { echo "myCom"; }
                echo " commentaire'>
                        <h2 class='titleCom'>" . htmlspecialchars($commentaire['nom']) . " ($nomRole)</h2>
                        <span>" . htmlspecialchars($commentaire['commentaire']) . "</span>
                      </div>";
            }
        }
        ?>
    </div>
    <?php
    if (isset($_SESSION['email']) && isset($_SESSION['idRole']) && isset($_SESSION['nom'])) {
        ?>
    <form action="pageEvent.php?event=<?php echo htmlspecialchars($event); ?>" method="post">
        <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($csrf_token); ?>">
        <div class='form-group'>
            <label for='commentaire'>Commentaire : </label>
            <input type='text' id='commentaire' name='commentaire' placeholder='Blabla...' required>
        </div>
        <button type='submit'>Envoyer</button>
    </form>
    <?php
    } else {
        echo "Vous devez être connecté pour envoyer des commentaires";
    }
    ?>
    <script>
        document.querySelector('.sectionCom').scroll({
            top:
                document.querySelector(".sectionCom").scrollHeight -
                document.querySelector(".sectionCom").clientHeight,
            left: 0,
            behavior: "instant",
        });
    </script>
</div>
</body>
</html>
