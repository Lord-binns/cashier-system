<?php
require_once('../actions/require_login.php');
require_once "../classes/Product.php";

// Ensure the cart exists
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "No items in the cart.";
    exit;
}

$product = new Product();
$total = 0;

// Calculate total price
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Handle payment submission (checkout)
if (isset($_POST['process_payment'])) {
    $payment = $_POST['payment'];
    $change = $payment - $total;

    if ($change >= 0) {
        // Only now update stock and clear the cart after successful checkout
        foreach ($_SESSION['cart'] as $product_id => $cart_item) {
            $product_details = $product->displaySpecificProduct($product_id);
            // Deduct the stock from the database
            $new_quantity = $product_details['quantity'] - $cart_item['quantity'];
            $product->editProduct($product_id, $product_details['product_name'], $product_details['price'], $new_quantity);
        }

        // Save the cart items, payment, total, and change to session for receipt
        $_SESSION['purchased_items'] = $_SESSION['cart'];
        $_SESSION['payment'] = $payment;
        $_SESSION['change'] = $change;
        $_SESSION['total'] = $total;

        // Clear the cart after successful checkout
        $_SESSION['cart'] = [];

        // Redirect to the receipt page after successful payment
        header("Location: receipt.php");
        exit;
    } else {
        echo "<p class='text-danger'>Insufficient payment. Please enter at least " . number_format($total, 2) . "</p>";
    }
}

// Handle deleting a product from the cart
if (isset($_GET['delete'])) {
    $product_id = $_GET['delete'];
    unset($_SESSION['cart'][$product_id]); // Remove from cart
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }

        .container {
            max-width: 800px;
        }

        .card {
            margin-top: 40px;
            border: none;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background-color: #007bff;
            color: white;
            text-align: center;
        }

        .form-control {
            border: 2px solid #ced4da;
            border-radius: 4px;
        }

        .btn-sec, .btn-success {
            background-color: #007bff;
            border-color: #007bff;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .table {
            margin-top: 20px;
        }

        th {
            background-color: grey;
        }

        .card-body {
            padding: 2rem;
        }
    </style>
</head>
<body>

    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="h2">Checkout</h1>
            </div>
            <div class="card-body">

                <!-- Cart Items -->
                <h2 class="h4 text-primary">Cart Items</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['cart'] as $product_id => $item): ?>
                            <tr>
                                <td><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td><?= number_format($item['price'], 2); ?></td>
                                <td><?= $item['quantity']; ?></td>
                                <td><?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                                <td>
                                    <!-- Delete Button -->
                                    <a href="cart.php?delete=<?= $product_id ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td colspan="3"><strong>Total</strong></td>
                            <td colspan="2"><strong><?= number_format($total, 2); ?></strong></td>
                        </tr>
                    </tbody>
                </table>

                <!-- Payment Form -->
                <form action="cart.php" method="post">
                    <div class="mb-3">
                        <label for="payment" class="form-label text-secondary">Enter Payment</label>
                        <input type="number" name="payment" id="payment" class="form-control" min="<?= $total; ?>" required>
                    </div>
                    <button type="submit" name="process_payment" class="btn btn-primary w-100">Process Payment</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
