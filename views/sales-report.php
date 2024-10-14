<?php
session_start();

// Check if the user is logged in and is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    // If the user is not an admin, show an alert and then redirect
    echo "<script>alert('Access denied: Please use an admin account!');</script>";
    echo "<script>window.location.href = 'cashier_dashboard.php';</script>"; // Redirect to dashboard
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

if (!empty($daily_sales_report)) {
    // Display the daily sales report
} else {
    echo "No daily sales report available.";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AB's Meat Shop Sales Report</title>
    <link rel="stylesheet" href="../css/dashboard.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            padding-top: 60px; /* adjust this value to match the height of your navigation bar */
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>

    <?php include 'navbar.php'; ?> <!-- Include the navbar -->

    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <h1 class="display-4 text-center">AB's Meat Shop</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
            <h2>Daily Report</h2>
                <table>
                    <tr>
                        <th>Order ID</th>
                        <th>Product Name</th>
                        <th>Price</th>
                        <th>Order Date</th>
                    </tr>
                    <?php foreach ($daily_sales_report as $report): ?>
                        <tr>
                            <td><?= $report['order_id']; ?></td>
                            <td><?= $report['product_name']; ?></td>
                            <td><?= number_format($report['price'], 2); ?></td>
                            <td><?= date('M d, Y', strtotime($report['order_date'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

                <div class=" row mt-5">
            <div class="col-md-6">
                <h2>Monthly Sales Report</h2>
                <table>
                    <tr>
                        <th>Month</th>
                        <th>Total Sales</th>
                    </tr>
                    <?php foreach ($monthly_sales_report as $report): ?>
                        <tr>
                            <td><?= date('M', mktime(0, 0, 0, $report['month'], 1)); ?></td>
                            <td><?= number_format($report['total_sales'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
            <div class="col-md-6">
                <h2>Yearly Sales Report</h2>
                <table>
                    <tr>
                        <th>Year</th>
                        <th>Total Sales</th>
                    </tr>
                    <?php foreach ($yearly_sales_report as $report): ?>
                        <tr>
                            <td><?= $report['year']; ?></td>
                            <td><?= number_format($report['total_sales'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12">
                <h2>Product Sales Report</h2>
                <table>
                    <tr>
                        <th>Product Name</th>
                        <th>Total Sales</th>
                    </tr>
                    <?php foreach ($product_sales_report as $report): ?>
                        <tr>
                            <td><?= $report['product_name']; ?></td>
                            <td><?= number_format($report['total_sales'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>