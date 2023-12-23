<?php
// Connexion à la base de données (à adapter avec vos paramètres)
$conn = mysqli_connect('127.0.0.1', 'root', '', 'ecom1_project');

// Fonction pour mettre à niveau un utilisateur au statut d'administrateur
function upgradeToAdmin($userId)
{
    global $conn;

    $query = "UPDATE user SET role_id = 1 WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

// Fonction pour supprimer un utilisateur
function deleteUser($userId)
{
    global $conn;

    // Supprimer les commandes associées à l'utilisateur
    $queryDeleteOrders = "DELETE FROM user_order WHERE user_id = ?";
    $stmtDeleteOrders = mysqli_prepare($conn, $queryDeleteOrders);
    mysqli_stmt_bind_param($stmtDeleteOrders, "i", $userId);
    mysqli_stmt_execute($stmtDeleteOrders);
    mysqli_stmt_close($stmtDeleteOrders);

    // Supprimer l'utilisateur
    $queryDeleteUser = "DELETE FROM user WHERE id = ?";
    $stmtDeleteUser = mysqli_prepare($conn, $queryDeleteUser);
    mysqli_stmt_bind_param($stmtDeleteUser, "i", $userId);
    mysqli_stmt_execute($stmtDeleteUser);
    mysqli_stmt_close($stmtDeleteUser);
}

// Fonction pour récupérer la liste des utilisateurs
function getUsers()
{
    global $conn;

    $query = "SELECT user.id, user.user_name, user.email, role.name AS role_name
              FROM user
              JOIN role ON user.role_id = role.id";
    $result = mysqli_query($conn, $query);
    $users = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $users;
}

// Fonction pour obtenir les détails de l'utilisateur
function getUserDetails($userId)
{
    global $conn;
    $query = "SELECT * FROM user WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $userDetails = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $userDetails;
}

// Fonction pour mettre à jour le profil de l'utilisateur
function updateUserProfile($userId, $firstName, $lastName, $billingAddressId, $shippingAddressId)
{
    global $conn;

    $query = "UPDATE user SET fname=?, lname=?, billing_address_id=?, shipping_address_id=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ssiii", $firstName, $lastName, $billingAddressId, $shippingAddressId, $userId);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}


// Fonction pour ajouter un super administrateur à la base de données
function addSuperAdmin()
{
    global $conn;

    $username = 'superadmin';
    $email = 'superadmin@admin.ca';
    $password = password_hash('12345678', PASSWORD_DEFAULT);
    $role_id = 1; // ID du rôle d'administrateur

    $query = "INSERT INTO user (user_name, email, pwd, role_id) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssi", $username, $email, $password, $role_id);
    mysqli_stmt_execute($stmt);

    $superAdminId = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);

    return $superAdminId;
}

// Fonction pour récupérer le super administrateur
function getSuperAdmin()
{
    global $conn;

    $query = "SELECT * FROM user WHERE user_name = 'superadmin'";
    $result = mysqli_query($conn, $query);
    $superAdmin = mysqli_fetch_assoc($result);

    return $superAdmin;
}

// Fonction pour ajouter un produit à la base de données
function addProduct($name, $quantity, $price, $img_url, $description)
{
    global $conn;

    $query = "INSERT INTO product (name, quantity, price, img_url, description) VALUES (?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sids", $name, $quantity, $price, $img_url, $description);
    mysqli_stmt_execute($stmt);

    $productId = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);

    return $productId;
}

// Fonction pour récupérer les articles dans le panier d'un utilisateur
function getCartItems($userId)
{
    global $conn;

    $query = "SELECT product.id, product.name, product.price, order_has_product.quantity
              FROM order_has_product
              JOIN product ON order_has_product.product_id = product.id
              JOIN user_order ON order_has_product.order_id = user_order.id
              WHERE user_order.user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $cartItems = mysqli_fetch_all($result, MYSQLI_ASSOC);

    mysqli_stmt_close($stmt);

    return $cartItems;
}

// Fonction pour calculer le total du panier d'un utilisateur
function calculateCartTotal($userId)
{
    global $conn;

    $query = "SELECT SUM(product.price * order_has_product.quantity) AS total
              FROM order_has_product
              JOIN product ON order_has_product.product_id = product.id
              JOIN user_order ON order_has_product.order_id = user_order.id
              WHERE user_order.user_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $total = mysqli_fetch_assoc($result)['total'];

    mysqli_stmt_close($stmt);

    return $total;
}

// Fonction pour récupérer les détails d'un produit
function getProductDetails($productId)
{
    global $conn;

    $query = "SELECT * FROM product WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "i", $productId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $productDetails = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $productDetails;
}

// Fonction pour ajouter un produit au panier d'un utilisateur
function addToCart($userId, $productId, $quantity)
{
    global $conn;

    // Créer une commande si l'utilisateur n'en a pas déjà une en cours
    $orderId = createOrder($userId);

    // Vérifier si le produit est déjà dans le panier
    $existingItem = getItemInCart($orderId, $productId);

    if ($existingItem) {
        // Mettre à jour la quantité du produit existant
        updateCartItemQuantity($orderId, $productId, $existingItem['quantity'] + $quantity);
    } else {
        // Ajouter le produit au panier
        $query = "INSERT INTO order_has_product (order_id, product_id, quantity, price)
                  VALUES (?, ?, ?, (SELECT price FROM product WHERE id = ?))";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iiii", $orderId, $productId, $quantity, $productId);
        mysqli_stmt_execute($stmt);

        mysqli_stmt_close($stmt);
    }
}

// Fonction pour récupérer la liste des produits
function getProducts()
{
    global $conn;

    $query = "SELECT * FROM product";
    $result = mysqli_query($conn, $query);
    $products = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $products;
}

// Fonction auxiliaire pour créer une commande pour un utilisateur
function createOrder($userId)
{
    global $conn;

    $query = "INSERT INTO user_order (ref, date, total, user_id) VALUES (?, CURRENT_DATE(), 0, ?)";
    $stmt = mysqli_prepare($conn, $query);
    $ref = generateOrderReference();
    mysqli_stmt_bind_param($stmt, "si", $ref, $userId);
    mysqli_stmt_execute($stmt);

    $orderId = mysqli_insert_id($conn);

    mysqli_stmt_close($stmt);

    return $orderId;
}

// Fonction auxiliaire pour générer une référence de commande unique
function generateOrderReference()
{
    return 'REF' . date('YmdHis') . rand(1000, 9999);
}

// Fonction auxiliaire pour récupérer un article dans le panier d'une commande
function getItemInCart($orderId, $productId)
{
    global $conn;

    $query = "SELECT * FROM order_has_product WHERE order_id = ? AND product_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $orderId, $productId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $item = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $item;
}

// Fonction auxiliaire pour mettre à jour la quantité d'un article dans le panier
function updateCartItemQuantity($orderId, $productId, $quantity)
{
    global $conn;

    $query = "UPDATE order_has_product SET quantity = ? WHERE order_id = ? AND product_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "iii", $quantity, $orderId, $productId);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);
}

// Fonction pour vérifier l'existence d'un utilisateur par son nom d'utilisateur ou email
function checkExistingUser($username, $email)
{
    global $conn;

    $query = "SELECT * FROM user WHERE user_name = ? OR email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ss", $username, $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    return $row; // Si l'utilisateur existe, renvoie ses informations, sinon renvoie null
}

// Fonction pour enregistrer un nouvel utilisateur
function registerUser($username, $email, $password, $firstName, $lastName)
{
    global $conn;

    // Vérifiez d'abord si l'utilisateur existe déjà
    $existingUser = checkExistingUser($username, $email);

    if ($existingUser) {
        // L'utilisateur existe déjà, vous pouvez afficher un message d'erreur ou rediriger
        // (vous pouvez personnaliser cela selon vos besoins)
        return false;
    }

    // L'utilisateur n'existe pas, vous pouvez procéder à l'inscription
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    $query = "INSERT INTO user (user_name, email, pwd, fname, lname, role_id) VALUES (?, ?, ?, ?, ?, 2)";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $username, $email, $hashedPassword, $firstName, $lastName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    return true; // L'inscription a réussi
}

function loginUser($username, $password)
{
    global $conn;

    $query = "SELECT * FROM user WHERE user_name=?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);

    mysqli_stmt_close($stmt);

    if ($row && password_verify($password, $row['pwd'])) {
        return $row; // Les informations d'identification sont correctes, renvoie les informations de l'utilisateur
    }

    return false; // Les informations d'identification sont incorrectes
}

// Fonction pour obtenir l'ID de l'utilisateur à partir de la session
function getUserId()
{

    if (isset($_SESSION['user_id'])) {
        return $_SESSION['user_id'];
    }

    return null;
}


// Fonction pour vérifier si l'utilisateur est un administrateur
function isAdmin()
{
    // Vous devrez adapter cette fonction en fonction de votre structure de base de données
    // Par exemple, vous pouvez vérifier le rôle de l'utilisateur dans la base de données.
    // Supposons que le rôle administrateur ait l'ID 1.
    $adminRoleId = 1;

    global $conn;
    $userId = getUserId(); // Vous devez implémenter la fonction getUserId pour obtenir l'ID de l'utilisateur actuel

    $query = "SELECT id FROM user WHERE id = ? AND role_id = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "ii", $userId, $adminRoleId);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $isAdmin = mysqli_fetch_assoc($result) ? true : false;

    mysqli_stmt_close($stmt);

    return $isAdmin;
}

// Fonction pour obtenir la liste de toutes les commandes
function getAllOrders()
{
    global $conn;

    $query = "SELECT * FROM user_order";
    $result = mysqli_query($conn, $query);

    return $result;
}
