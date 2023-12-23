<?php
include '../functions.php';

$title = "Validation de la commande";
$style_url = "../style/checkout.css";
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

// Assurez-vous que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: ../user/login.php');
    exit();
}

// Récupérer le contenu du panier de l'utilisateur
$userId = $_SESSION['user_id'];
$cartItems = getCartItems($userId);

// Calculer le total du panier
$total = calculateCartTotal($cartItems);
?>

<h2>Validation de la commande</h2>

<?php if ($cartItems) : ?>
    <h3>Produits dans votre panier :</h3>
    <ul>
        <?php foreach ($cartItems as $cartItem) : ?>
            <li>
                <?php echo $cartItem['product_name']; ?>
                - Quantité : <?php echo $cartItem['quantity']; ?>
                - Prix unitaire : <?php echo $cartItem['price']; ?> $
            </li>
        <?php endforeach; ?>
    </ul>

    <p>Total de la commande : <?php echo $total; ?> $</p>

    <form action="process_order.php" method="post">
        <!-- Ajoutez des champs pour les détails de livraison et de paiement selon vos besoins -->
        <label for="shipping_address">Adresse de livraison :</label>
        <input type="text" id="shipping_address" name="shipping_address" required><br>

        <label for="payment_method">Méthode de paiement :</label>
        <select id="payment_method" name="payment_method" required>
            <option value="paypal">PayPal</option>
            <!-- Ajoutez d'autres options de paiement selon vos besoins -->
        </select><br>

        <button type="submit">Confirmer la commande</button>
    </form>
<?php else : ?>
    <p>Votre panier est vide.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>
