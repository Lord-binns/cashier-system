<?php
require_once "Database.php";

class Sales extends Database {

    public function insertOrderData($user_id, $purchased_items, $payment, $change, $total) {
        try {
            // Get the current date
            $current_date = date('Y-m-d H:i:s');
            
            // Insert the order data into the database
            $sql = "INSERT INTO orders (user_id, order_date, total_cost)
                    VALUES (?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("isd", $user_id, $current_date, $total);
            $stmt->execute();
        
            // Get the order id of the newly inserted order
            $order_id = $stmt->insert_id;
        
            // Insert the order items into the database
            $sql = "INSERT INTO order_items (order_id, product_id, quantity, price)
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->conn->prepare($sql);
            foreach ($purchased_items as $product_id => $item) {
                $stmt->bind_param("iiid", $order_id, $product_id, $item['quantity'], $item['price']);
                $stmt->execute();
            }
        } catch (Exception $e) {
            // Log the error message
            error_log("Error inserting order data: " . $e->getMessage());
            
            // Display a user-friendly error message
            echo "Error inserting order data: " . $e->getMessage();
        }
    }

    public function getDailySalesReport($user_id) {
        try {
            // Get the current date
            $current_date = date('Y-m-d');
            
            // Get the daily sales report
            $sql = "SELECT o.order_id, p.product_name, oi.quantity, oi.price, o.order_date
                    FROM orders o
                    JOIN order_items oi ON o.order_id = oi.order_id
                    JOIN products p ON oi.product_id = p.product_id
                    WHERE o.user_id = ?
                    AND DATE(o.order_date) = ?
                    ORDER BY o.order_date DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("is", $user_id, $current_date);
            $stmt->execute();
            $result = $stmt->get_result();
            $daily_sales_report = $result->fetch_all(MYSQLI_ASSOC);
            return $daily_sales_report;
        } catch (Exception $e) {
            // Log the error message
            error_log("Error retrieving daily sales report: " . $e->getMessage());
            
            // Display a user-friendly error message
            echo "Error retrieving daily sales report. Please try again later.";
        }
    }
    
    public function getMonthlySalesReport($user_id) {
        try {
            // Get the monthly sales report
            $sql = "SELECT MONTH(o.order_date) AS month, SUM(oi.quantity * oi.price) AS total_sales
                    FROM orders o
                    JOIN order_items oi ON o.order_id = oi.order_id
                    WHERE o.user_id = ?
                    GROUP BY MONTH(o.order_date)
                    ORDER BY MONTH(o.order_date) DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $monthly_sales_report = $result->fetch_all(MYSQLI_ASSOC);
            return $monthly_sales_report;
        } catch (Exception $e) {
            // Handle the database error
            echo "Error getting monthly sales report: " . $e->getMessage();
        }
    }
    
    public function getYearlySalesReport($user_id) {
        try {
            // Get the yearly sales report
            $sql = "SELECT YEAR(o.order_date) AS year, SUM(oi.quantity * oi.price) AS total_sales
                    FROM orders o
                    JOIN order_items oi ON o.order_id = oi.order_id
                    WHERE o.user_id = ?
                    GROUP BY YEAR(o.order_date)
                    ORDER BY YEAR(o.order_date) DESC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $yearly_sales_report = $result->fetch_all(MYSQLI_ASSOC);
            return $yearly_sales_report;
        } catch (Exception $e) {
            // Handle the database error
            echo "Error getting yearly sales report: " . $e->getMessage();
        }
    }
    
    public function getProductSalesReport($user_id) {
        try {
            // Get the product sales report
            $sql = "SELECT p.product_name, SUM(oi.quantity * oi.price) AS total_sales
                    FROM orders o
                    JOIN order_items oi ON o.order_id = oi.order_id
                    JOIN products p ON oi.product_id = p.product_id
                    WHERE o.user_id = ?
                    GROUP BY p.product_name
                    ORDER BY p.product_name ASC";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $product_sales_report = $result->fetch_all(MYSQLI_ASSOC);
            return $product_sales_report;
        } catch (Exception $e) {
            // Handle the database error
            echo "Error getting product sales report: " . $e->getMessage();
        }
    }

}
?>