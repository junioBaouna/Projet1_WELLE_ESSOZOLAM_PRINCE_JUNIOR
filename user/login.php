<?php
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Ajoutez une validation des données si nécessaire

    $user = loginUser($username, $password);
    if ($user) {
        // Démarrez la session et enregistrez l'ID utilisateur
        session_start();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['user_name'];

        // Rediriger vers la page d'accueil ou autre
        header('Location: ../index.php');
        exit();
    } else {
        // Afficher un message d'erreur
        echo "Identifiant ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/login.css">
    <title>Login</title>
</head>

<body>

    <h2>Connexion</h2>

    <form action="login.php" method="post">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required><br>

        <button type="submit">Se connecter</button>
    </form>

</body>

</html>