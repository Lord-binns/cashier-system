<?php
require_once('../actions/require_login.php');
require_once "../classes/Product.php";

$product = new Product();
$products = $product->displayProducts();

// Initialize cart if it's not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle adding a product to the cart
if (isset($_POST['add_to_cart'])) {
    $product_id = $_POST['product_id'];
    $quantity = 1; // Default quantity of 1
    $product_details = $product->displaySpecificProduct($product_id);
    
    // Check stock
    $current_cart_quantity = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id]['quantity'] : 0;
    $total_requested_quantity = $current_cart_quantity + $quantity;

    if ($product_details && $total_requested_quantity <= $product_details['quantity']) {
        if (!isset($_SESSION['cart'][$product_id])) {
            $_SESSION['cart'][$product_id] = [
                'name' => $product_details['product_name'], 
                'price' => $product_details['price'], 
                'quantity' => $quantity
            ];
        } else {
            $_SESSION['cart'][$product_id]['quantity'] += $quantity;
        }
    } else {
        echo "<p class='text-danger'>Not enough stock available for this product.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cashiering Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">


    <style>
        body {
            background-color: #343a40; /* Dark background color */
            color: white; /* Change text color to white */
        }
        h1 {
            color: #212529; /* Dark color for h1 text */
        }
        .card {
    background-color: rgba(73, 80, 87, 0.8); /* Semi-transparent dark background */
    border: 1px solid #343a40; /* Dark border color */
}

    </style>
</head>

<body>
    <?php include 'navbar.php'; ?> <!-- Include the navbar -->

    <div class="container mt-5 pt-5">
        <h1 class="display-4 text-center">Cashiering Dashboard</h1>

        <h2>Select Products</h2>
        <div class="row">
            <?php foreach ($products as $p): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h5 class="card-title"><?= $p['product_name']; ?></h5>
                        <p class="card-text">Price: $<?= $p['price']; ?></p>
                        <p class="card-text">Stock: <?= $p['quantity']; ?></p>
                    </div>
                    <div class="card-footer text-end">
                        <form action="dashboard.php" method="post">
                            <input type="hidden" name="product_id" value="<?= $p['id']; ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-primary">
                                <i class="bi bi-cart-plus"></i> Add to Cart
                            </button>
                            <?php 
                            if (isset($_SESSION['cart'][$p['id']])): 
                                $cart_quantity = $_SESSION['cart'][$p['id']]['quantity']; 
                            ?>
                                <span class="badge bg-secondary ms-2">In Cart: <?= $cart_quantity; ?></span>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <div class="text-end mt-3">
            <a href="cart.php" class="btn btn-success">
                <i class="bi bi-cart-check"></i> View Cart / Checkout
            </a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
