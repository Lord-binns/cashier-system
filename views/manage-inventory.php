<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Access denied: Please use an admin account!');</script>";
    echo "<script>window.location.href = 'dashboard.php';</script>";
    exit;
}

require_once('../actions/require_login.php');
require_once "../classes/Product.php";

$product = new Product();
$products = $product->displayProducts();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Inventory</title>
    <link rel="stylesheet" href="../css/inventory.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #343a40; /* Dark background */
            color: white; /* Light text color */
            height: 100vh; /* Full viewport height */
            display: flex; /* Flexbox for centering */
            align-items: center; /* Vertically center */
            justify-content: center; /* Horizontally center */
            margin: 0; /* Remove default margin */
        }
        h1 {
            color: white; /* Dark color for h1 text */
        }
        .card {
            background-color: rgba(73, 80, 87, 0.8); /* Semi-transparent dark background */
            border: 2px solid white; /* Dark border color */
            width: 80%; /* Set a width for the card */
        }
        .card-header {
            background-color: #495057; /* Darker header background */
        }
        .table th, .table td {
            color: white; /* Keep text color white in the table */
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?> <!-- Include the navbar -->

    <div class="card">
        <div class="card-header">
            <h1 class="display-4 text-center">Inventory</h1>
        </div>
        <div class="card-body">
            <!-- Add Product Button -->
            <div class="text-end mb-3">
                <a href="add-product.php" class="btn btn-success">Add Product</a>
            </div>

            <!-- Product Table -->
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($products)): ?>
                        <?php foreach ($products as $p): ?>
                            <tr>
                                <td><?= htmlspecialchars($p['product_id'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= htmlspecialchars($p['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= number_format($p['price'], 2); ?></td>
                                <td><?= $p['quantity']; ?></td>
                                <td>
                                    <!-- Edit and Delete buttons -->
                                    <a href="edit-product.php?id=<?= $p['product_id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                    <a href="../actions/delete-product.php?id=<?= $p['product_id']; ?>" class="btn btn-danger btn-sm"
                                       onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No products available</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
