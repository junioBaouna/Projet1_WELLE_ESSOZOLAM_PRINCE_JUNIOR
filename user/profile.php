<?php
include '../functions.php';

$title = "Profil";
$style_url = "../style/profil.css";
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

// Vérifier si l'utilisateur est connecté, sinon rediriger vers la page de connexion
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Récupérer les détails de l'utilisateur à partir de la session
$userId = $_SESSION['user_id'];
$userDetails = getUserDetails($userId);

// Si le formulaire de mise à jour est soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $billingAddressId = $_POST['billing_address_id'];
    $shippingAddressId = $_POST['shipping_address_id'];

    // Ajoutez une validation des données si nécessaire

    // Mettre à jour le profil de l'utilisateur
    updateUserProfile($userId, $firstName, $lastName, $billingAddressId, $shippingAddressId);

    // Rediriger vers la page du profil pour afficher les changements
    header('Location: profile.php');
    exit();
}

?>



<h2><?php echo $title; ?></h2>

<p>Bienvenue, <?php echo $userDetails['fname'] . ' ' . $userDetails['lname']; ?>!</p>

<form action="profile.php" method="post">
    <label for="first_name">Prénom:</label>
    <input type="text" id="first_name" name="first_name" value="<?php echo $userDetails['fname']; ?>" required><br>

    <label for="last_name">Nom de famille:</label>
    <input type="text" id="last_name" name="last_name" value="<?php echo $userDetails['lname']; ?>" required><br>

    <label for="billing_address_id">ID de l'adresse de facturation:</label>
    <input type="text" id="billing_address_id" name="billing_address_id" value="<?php echo $userDetails['billing_address_id']; ?>" required><br>

    <label for="shipping_address_id">ID de l'adresse de livraison:</label>
    <input type="text" id="shipping_address_id" name="shipping_address_id" value="<?php echo $userDetails['shipping_address_id']; ?>" required><br>

    <button type="submit">Mettre à jour le profil</button>
</form>

<p><a href="../logout.php">Se déconnecter</a></p>

</main>
</body>

</html>