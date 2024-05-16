<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link rel="stylesheet" href="../css/connexion.css">
</head>
<body>
<div class="container">
    <div class="form-container"><?php if (isset($_GET['status'])){ echo "<h1 style='color: #004a12'>Inscription " . $_GET['status'] . "</h1>";}?>
        <h2>Connexion</h2>
        <form action="../PHP/login.php" method="post">

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