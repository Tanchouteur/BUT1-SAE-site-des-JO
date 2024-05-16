
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Connexion</title>
    <link rel="stylesheet" href="../css/connexion.css">
</head>
<body>
<?php
require_once '../../import/BDD.php';

session_start();

$email = $_POST['email'];
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
    if (password_verify($pass, $row['mdp'])){

        $_SESSION['email'] = $row['email'];
        $_SESSION['nom'] = $row['login'];

        header("location:../../index.php?status=sucess");

    }else{
        header("location:../html/singin.php?status=Login ou mot de passe incorre ct");
    }
} else {
    header("location:../html/singin.php?status=Login ou mot de passe incorrect");
}
echo 'cc';
?>
</body>
</html>