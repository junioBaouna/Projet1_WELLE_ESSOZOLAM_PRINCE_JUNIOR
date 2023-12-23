<?php
include '../functions.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];

    // Ajoutez une validation des données si nécessaire

    // Vérifiez si l'utilisateur existe déjà
    $existingUser = checkExistingUser($username, $email);
    if ($existingUser) {
        echo "L'utilisateur existe déjà. Veuillez choisir un autre nom d'utilisateur ou une autre adresse e-mail.";
    } else {
        // Enregistrez le nouvel utilisateur
        registerUser($username, $email, $password, $firstName, $lastName);

        // Redirigez vers la page de connexion ou autre
        header('Location: ./login.php');
        exit();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/register.css">
    <title>Inscription</title>
</head>

<body>

    <h2>Inscription</h2>

    <form action="register.php" method="post">
        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required><br>

        <label for="email">Adresse e-mail:</label>
        <input type="email" id="email" name="email" required><br>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required><br>

        <label for="first_name">Prénom:</label>
        <input type="text" id="first_name" name="first_name" required><br>

        <label for="last_name">Nom de famille:</label>
        <input type="text" id="last_name" name="last_name" required><br>

        <button type="submit">S'inscrire</button>
    </form>

</body>

</html>