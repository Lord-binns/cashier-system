<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // If the user is not an admin, show an alert and then redirect
    echo "<script>alert('Access denied: Please use an admin account!');</script>";
    echo "<script>window.location.href = 'dashboard.php';</script>"; // Redirect to dashboard
    exit; // Stop further execution of the script
}

require_once '../classes/sales.php';

$sales = new Sales();

try {
    $daily_sales_report = $sales->getDailySalesReport($_SESSION['user_id']);
    $monthly_sales_report = $sales->getMonthlySalesReport($_SESSION['user_id']);
    $yearly_sales_report = $sales->getYearlySalesReport($_SESSION['user_id']);
    $product_sales_report = $sales->getProductSalesReport($_SESSION['user_id']);
} catch (Exception $e) {
    // Log the error message
    error_log("Error retrieving sales reports: " . $e->getMessage());
    
    // Display a user-friendly error message
    echo "Error retrieving sales reports. Please try again later.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Report</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">

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
        .card {
            background-color: rgba(73, 80, 87, 0.8); /* Semi-transparent dark background */
            border: 1px solid #343a40; /* Dark border color */
            width: 80%; /* Set width of the card */
        }
        .card-header {
            background-color: #495057; /* Darker header background */
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            color: white; /* Light text color in the table */
        }
        th {
            background-color: #495057; /* Dark background for headers */
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?> <!-- Include the navbar -->

    <div class="card">
        <div class="card-header text-center">
            <h1 class="display-4">Sales Report</h1>
        </div>
        <div class="card-body">
            <div class="row mb-5">
                <div class="col-md-12">
                    <h2>Daily Sales Report</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Order Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($daily_sales_report)): ?>
                                <?php foreach ($daily_sales_report as $report): ?>
                                    <tr>
                                        <td><?= $report['order_id']; ?></td>
                                        <td><?= htmlspecialchars($report['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                        <td><?= $report['quantity']; ?></td>
                                        <td><?= number_format($report['price'], 2); ?></td>
                                        <td><?= date('M d, Y', strtotime($report['order_date'])); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="5">No daily sales report available.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mb-5">
                <div class="col-md-6">
                    <h2>Monthly Sales Report</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Month</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($monthly_sales_report as $report): ?>
                                <tr>
                                    <td><?= date('M', mktime(0, 0, 0, $report['month'], 1)); ?></td>
                                    <td><?= number_format($report['total_sales'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-6">
                    <h2>Yearly Sales Report</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Year</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($yearly_sales_report as $report): ?>
                                <tr>
                                    <td><?= $report['year']; ?></td>
                                    <td><?= number_format($report['total_sales'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <h2>Product Sales Report</h2>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Total Sales</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($product_sales_report as $report): ?>
                                <tr>
                                    <td><?= htmlspecialchars($report['product_name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                    <td><?= number_format($report['total_sales'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
