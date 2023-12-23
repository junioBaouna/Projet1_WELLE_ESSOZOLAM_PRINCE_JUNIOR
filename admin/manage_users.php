<?php
include '../functions.php';

$title = "Gestion des utilisateurs";
$style_url = "../style/manage_users.css";
$index_url = "../index.php";
$product_list_url = "../products/product_list.php";
$view_cart_url = "../cart/view_cart.php";
$profile_url = "../user/profile.php";
$register_url = "../user/register.php";
$login_url = "../user/login.php";
$add_product_url = "../admin/add_product.php";
$manage_users_url = "../admin/manage_users.php";
$view_orders_url = "../admin/view_orders.php";
$logout_url = "../logout.php";
include '../includes/header.php';

// Vérifiez si l'utilisateur est connecté et est un administrateur
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] !== 1) {
    header('Location: login.php');
    exit();
}

// Traitement des actions d'administration si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['upgrade_to_admin']) && isset($_POST['user_id'])) {
        // Mise à niveau d'un utilisateur au statut d'administrateur
        $userId = $_POST['user_id'];
        upgradeToAdmin($userId);
    } elseif (isset($_POST['delete_user']) && isset($_POST['user_id'])) {
        // Suppression d'un utilisateur
        $userId = $_POST['user_id'];
        deleteUser($userId);
    }
}

// Récupérez la liste des utilisateurs
$users = getUsers();
?>

<h2><?php echo $title; ?></h2>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nom d'utilisateur</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($users as $user) : ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['user_name']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role_name']; ?></td>
                <td>
                    <?php if ($user['role_id'] == 2) : ?>
                        <!-- Bouton de mise à niveau au statut d'administrateur -->
                        <form action="manage_users.php" method="post">
                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                            <button type="submit" name="upgrade_to_admin">Promouvoir en admin</button>
                        </form>
                    <?php endif; ?>

                    <!-- Bouton de suppression d'utilisateur -->
                    <form action="manage_users.php" method="post">
                        <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                        <button type="submit" name="delete_user">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php include '../includes/footer.php'; ?>