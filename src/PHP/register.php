
<?php
require_once '../../import/BDD.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Récupère les données du formulaire
    $user = $_POST['username'];
    $email = strtolower($_POST['email']);
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);


// Prépare et exécute la requête d'insertion
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

            header("location:../html/singin.php?status=1&msg=Inscription Réussi");
        } else {
            header("location:../html/singup.php?status=0&msg=Une erreur est survenue");
        }
    } else {
        header("location:../html/singup.php?status=0&msg=Email deja existant");
    }
}

?>
