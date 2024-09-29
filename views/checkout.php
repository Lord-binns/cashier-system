<?php
require_once('../actions/require_login.php');
session_start(); // Start session to access the cart

// Check if the cart exists
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    echo "No items in the cart.";
    exit;
}

$total = 0; // Initialize total amount

// Calculate total amount for the cart
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}

// Handle payment submission
if (isset($_POST['process_payment'])) {
    $payment = $_POST['payment'];
    $change = $payment - $total;

    if ($change >= 0) {
        // Success: Deduct stock and clear cart
        require_once "../classes/Product.php";
        $product = new Product();

        // Store the purchased items in the session
        $_SESSION['purchased_items'] = $_SESSION['cart'];
        $_SESSION['payment'] = $payment;
        $_SESSION['change'] = $change;
        $_SESSION['total'] = $total;

        foreach ($_SESSION['cart'] as $product_id => $cart_item) {
            $product->buyProduct($product_id, $cart_item['quantity']); // Deduct stock
        }

        // Clear cart after checkout
        $_SESSION['cart'] = [];
        header('Location: receipt.php');
        exit;
    } else {
        echo "<p class='text-danger'>Insufficient payment. Please enter at least " . number_format($total, 2) . "</p>";
    }
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
            background-color: #f8f9fa; /* Light gray background */
            color: #343a40; /* Dark gray text */
        }
        h1, h2 {
            color: #0056b3; /* Dark blue for headers */
        }
        .table {
            background-color: #ffffff; /* White table background */
            border-radius: 5px; /* Rounded corners */
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
        }
        .table th, .table td {
            vertical-align: middle; /* Center align text in table */
        }
        .btn-success {
            background-color: #28a745; /* Green button */
            border-color: #28a745; /* Same green border */
        }
        .btn-secondary {
            background-color: #6c757d; /* Gray button */
            border-color: #6c757d; /* Same gray border */
        }
        .text-danger {
            color: #dc3545; /* Bootstrap red for error messages */
        }
    </style>
</head>
<body>
    
    <div class="container mt-5">
        <h1 class="display-4 text-center">Checkout</h1>

        <!-- Cart Details -->
        <h2>Cart Items</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td><?= number_format($item['price'], 2); ?></td>
                        <td><?= $item['quantity']; ?></td>
                        <td><?= number_format($item['price'] * $item['quantity'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong><?= number_format($total, 2); ?></strong></td>
                </tr>
            </tbody>
        </table>

        <!-- Payment Form -->
        <form action="checkout.php" method="post">
            <div class="mb-3">
                <label for="payment" class="form-label">Enter Payment</label>
                <input type="number" name="payment" id="payment" class="form-control" min="<?= $total; ?>" required>
            </div>
            <button type="submit" name="process_payment" class="btn btn-success">Process Payment</button>
        </form>

        <div class="mt-3">
            <a href="dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
