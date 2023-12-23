<?php
include '../functions.php';

$title = "Détails du produit";
$style_url = "../style/product_details.css";
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

// Assurez-vous que l'ID du produit est passé dans l'URL
if (!isset($_GET['product_id'])) {
    // Redirigez vers une page d'erreur ou une autre page appropriée
    header('Location: error.php');
    exit();
}

// Récupérez l'ID du produit depuis l'URL
$productId = $_GET['product_id'];

// Obtenez les détails du produit
$productDetails = getProductDetails($productId);

// Vérifiez si le formulaire d'ajout au panier est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_to_cart'])) {
    $quantity = $_POST['quantity'];

    // Ajoutez une validation des données si nécessaire

    // Ajoutez le produit au panier (implémentation de addToCart nécessaire)
    addToCart($_SESSION['user_id'], $productId, $quantity);

    // Redirigez vers la page du panier ou une autre page appropriée
    // header('Location: ../cart/view_cart.php');
    // exit();
}
?>

<h2><?php echo $title; ?></h2>

<?php if ($productDetails) : ?>
    <h3><?php echo $productDetails['name']; ?></h3>
    <p><?php echo $productDetails['description']; ?></p>
    <p>Prix: <?php echo $productDetails['price']; ?> $</p>
    <?php if (isset($_SESSION['user_id'])) : ?>
        <form action="product_details.php?product_id=<?php echo $productId; ?>" method="post">
            <label for="quantity">Quantité:</label>
            <input type="number" id="quantity" name="quantity" value="1" min="1" required>
            <button type="submit" name="add_to_cart">Ajouter au panier</button>
        </form>
    <?php else : ?>
        <p>Veuillez-vous <a href="">authentifier</a> pour ajouter le produit au panier.</p>
    <?php endif ?>
<?php else : ?>
    <p>Produit non trouvé.</p>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>