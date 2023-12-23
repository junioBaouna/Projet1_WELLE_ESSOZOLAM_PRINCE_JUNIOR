<?php
include '../config.php';
include '../functions.php';

$title = "Panier";
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

// Récupérez le contenu du panier depuis la session
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];

// Récupérez l'ID de l'utilisateur connecté
$userId = getUserId();

?>

<h2><?php echo $title; ?></h2>

<?php if (!empty($cartItems)) : ?>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité</th>
                <th>Prix unitaire</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cartItems as $cartItem) : ?>
                <tr>
                    <td><?php echo $cartItem['name']; ?></td>
                    <td><?php echo $cartItem['quantity']; ?></td>
                    <td><?php echo $cartItem['price']; ?> $</td>
                    <td><?php echo $cartItem['quantity'] * $cartItem['price']; ?> $</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>Total du panier : <?php echo calculateCartTotal($userId); ?> $</p>

    <a href="checkout.php">Passer à la caisse</a>

<?php else : ?>
    <p>Votre panier est vide.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>