<?php
include 'config.php';
include 'functions.php';

$title = "Mon E-Commerce";
$style_url = "style/index.css";
$index_url = "index.php";
$product_list_url = "products/product_list.php";
$view_cart_url = "cart/view_cart.php";
$profile_url = "user/profile.php";
$register_url = "user/register.php";
$login_url = "user/login.php";
$add_product_url = "admin/add_product.php";
$manage_users_url = "admin/manage_users.php";
$view_orders_url = "admin/view_orders.php";
$logout_url = "logout.php";
include 'includes/header.php';
?>

<section>
    <h2>Bienvenue sur Notre Plateforme de Commerce Électronique</h2>
    <p>Découvrez une expérience de shopping en ligne exceptionnelle avec notre large gamme de produits de haute qualité.</p>

    <p>Pour explorer nos produits, veuillez visiter notre <a href="products/product_list.php">liste complète des produits</a>.</p>
</section>

<?php include 'includes/footer.php'; ?>
