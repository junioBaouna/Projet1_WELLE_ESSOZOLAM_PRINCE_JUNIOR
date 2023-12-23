<?php
include '../functions.php';

$title = "Ajouter un produit";
$style_url = "../style/ajout_produit.css";
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

// Vérifiez si le formulaire d'ajout de produit est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $img_url = $_POST['img_url'];
    $description = $_POST['description'];

    // Ajoutez une validation des données si nécessaire

    // Ajoutez le produit à la base de données
    $productId = addProduct($name, $quantity, $price, $img_url, $description);

    if ($productId) {
        echo "Produit ajouté avec succès. ID du produit : $productId";
    } else {
        echo "Erreur lors de l'ajout du produit.";
    }
}

?>

<h2><?php echo $title; ?></h2>

<form action="add_product.php" method="post">
    <label for="name">Nom du produit:</label>
    <input type="text" id="name" name="name" required><br>

    <label for="quantity">Quantité:</label>
    <input type="number" id="quantity" name="quantity" value="0" min="0" required><br>

    <label for="price">Prix:</label>
    <input type="number" id="price" name="price" step="0.01" min="0" required><br>

    <label for="img_url">URL de l'image:</label>
    <input type="text" id="img_url" name="img_url" required><br>

    <label for="description">Description:</label>
    <textarea id="description" name="description" rows="4" required></textarea><br>

    <button type="submit">Ajouter le produit</button>
</form>

<?php include '../includes/footer.php'; ?>