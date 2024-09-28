<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // If the user is not an admin, show an alert and then redirect
    echo "<script>alert('Access denied: Please use an admin account!');</script>";
    echo "<script>window.location.href = 'dashboard.php';</script>"; // Redirect to dashboard
    exit; // Stop further execution of the script
}


// If the user is an admin, continue with inventory management code
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
    <title>Cashiering Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'navbar.php'; ?> <!-- Include the navbar -->

    <div class="container mt-5">
        <h1 class="display-4 text-center">Manage Inventory</h1>

        <!-- Back to Dashboard Button -->
        <div class="mb-3 text-start">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>

        <!-- Add Product Button -->
        <div class="text-end mb-3">
            <a href="add-product.php" class="btn btn-success">Add Product</a>
        </div>

        <!-- Display All Products -->
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Actions</th> <!-- Edit and Delete options -->
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($products)): ?>
                    <?php foreach ($products as $p): ?>
                        <tr>
                            <td><?= htmlspecialchars($p['id'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= htmlspecialchars($p['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                            <td><?= number_format($p['price'], 2); ?></td>
                            <td><?= $p['quantity']; ?></td>
                            <td>
                                <!-- Edit Button -->
                                <a href="edit-product.php?id=<?= $p['id']; ?>" class="btn btn-warning btn-sm">Edit</a>

                                <!-- Delete Button -->
                                <a href="../actions/delete-product.php?id=<?= $p['id']; ?>" class="btn btn-danger btn-sm"
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

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>