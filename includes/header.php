<?php
// Assurez-vous que la session a été démarrée
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $style_url; ?>">
    <title><?php echo $title; ?></title>
</head>

<body>

    <header>
        <h1>Mon E-commerce</h1>
        <nav>
            <ul>
                <li><a href="<?php echo $index_url; ?>">Accueil</a></li>
                <li><a href="<?php echo $product_list_url; ?>">Nos produits</a></li>
                <?php

                // Initialisez role_id à 0 si non défini
                $role_id = $_SESSION['role_id'] ?? 0;

                if (isset($_SESSION['user_id'])) {
                    // Utilisateur connecté
                    echo '<li><a href="' . $view_cart_url . '">Panier</a></li>';
                    echo '<li><a href="' . $profile_url . '">Profil</a></li>';

                    // on verifie si l'utilisateur est un administrateur
                    if ($role_id == 1) {
                        echo '<li><a href="' . $add_product_url . '">Ajouter un produit</a></li>';
                        echo '<li><a href="' . $manage_users_url . '">Gérer les utilisateurs</a></li>';
                        echo '<li><a href="' . $view_orders_url . '">Voir les commandes</a></li>';
                    }

                    echo '<li><a href="' . $logout_url . '">Déconnexion</a></li>';
                } else {
                    // Utilisateur non connecté
                    echo '<li><a href="' . $register_url . '">Inscription</a></li>';
                    echo '<li><a href="' . $login_url . '">Connexion</a></li>';
                }
                ?>
            </ul>
        </nav>
    </header>

    <main>