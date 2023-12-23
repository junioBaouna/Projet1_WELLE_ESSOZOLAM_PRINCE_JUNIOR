<?php
include '../functions.php';

$title = "Liste des produits";
$style_url = "../style/product_list.css";
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

// Récupérer la liste des produits
$products = getProducts();
?>

<h2><?php echo $title; ?></h2>

<?php if ($products && count($products) > 0) : ?>
    <ul>
        <?php foreach ($products as $product) : ?>
            <li>
                <a href="product_details.php?product_id=<?php echo $product['id']; ?>">
                    <?php echo $product['name']; ?>
                </a>
                - Prix: <?php echo $product['price']; ?> $
            </li>
        <?php endforeach; ?>
    </ul>
<?php else : ?>
    <p>Aucun produit disponible pour le moment.</p>
<?php endif; ?>


<?php include '../includes/footer.php'; ?>