<?php
include '../config.php';
include '../functions.php';

$title = "Liste des commandes";
$style_url = "../style/view_cart.css";
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

// Vérifier si l'utilisateur est un administrateur
if (!isAdmin()) {
    // Rediriger vers une page d'erreur ou une autre page appropriée
    header('Location: error.php');
    exit();
}

// Obtenir la liste de toutes les commandes
$allOrders = getAllOrders();

?>

<h2><?php echo $title; ?></h2>

<?php if ($allOrders && mysqli_num_rows($allOrders) > 0) : ?>
    <table border="1">
        <tr>
            <th>ID Commande</th>
            <th>Référence</th>
            <th>Date</th>
            <th>Total</th>
            <th>ID Utilisateur</th>
        </tr>
        <?php while ($order = mysqli_fetch_assoc($allOrders)) : ?>
            <tr>
                <td><?php echo $order['id']; ?></td>
                <td><?php echo $order['ref']; ?></td>
                <td><?php echo $order['date']; ?></td>
                <td><?php echo $order['total']; ?> $</td>
                <td><?php echo $order['user_id']; ?></td>
            </tr>
        <?php endwhile; ?>
    </table>
<?php else : ?>
    <p>Aucune commande disponible pour le moment.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>