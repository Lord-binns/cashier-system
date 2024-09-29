<?php
require_once "Database.php";

class Product extends Database {
    public function displayProducts() {
        $sql = "SELECT * FROM products";
        $result = $this->conn->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Method to add a new product
    public function addProduct($product_name, $price, $quantity) {
        // SQL to insert a new product
        $sql = "INSERT INTO products (product_name, price, quantity) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdi", $product_name, $price, $quantity);  // s = string, d = double, i = integer

        // Execute the query
        if ($stmt->execute()) {
            return true;  // Product added successfully
        } else {
            return false;  // Failed to add product
        }
    }

    public function editProduct($id, $product_name, $price, $quantity) {
        $sql = "UPDATE products SET product_name = ?, price = ?, quantity = ? WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->bind_param("sdii", $product_name, $price, $quantity, $id); // Bind the parameters
        
        if ($stmt->execute()) {
            return true; // Return true if update succeeds
        } else {
            return false; // Return false if update fails
        }
    } 
    
    public function buyProduct($id, $buy_quantity) {
        // Fetch product details
        $product = $this->displaySpecificProduct($id);
    
        // Check if the product is null (not found)
        if ($product === null) {
            die("Product not found. Cannot proceed with the purchase.");  // Handle the null case
        }
    
        // Now check if the quantity is sufficient
        if ($product['quantity'] >= $buy_quantity) {
            // Calculate new quantity
            $new_quantity = $product['quantity'] - $buy_quantity;
    
            // Update product quantity in the database
            $sql = "UPDATE products SET quantity = ? WHERE id = ?";
            $stmt = $this->conn->prepare($sql);
            $stmt->bind_param("ii", $new_quantity, $id);
    
            if ($stmt->execute()) {
                return true;  // Purchase successful
            } else {
                return false;  // Failed to update the database
            }
        } else {
            // If there is not enough stock
            die("Not enough stock available.");
        }
    }
    
    public function displaySpecificProduct($product_id) {
        // Prepare SQL query
        $sql = "SELECT * FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql);
    
        // Check if the SQL statement was prepared correctly
        if (!$stmt) {
            die("Error preparing SQL statement: " . $this->conn->error);
        }
    
        // Bind the product ID
        $stmt->bind_param("i", $product_id);
    
        // Check if the query executed successfully
        if (!$stmt->execute()) {
            die("Error executing SQL statement: " . $stmt->error);
        }
    
        // Get the result
        $result = $stmt->get_result();
    
        // Check if any rows were returned
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();  // Return the product details
        } else {
            echo "No product found with ID: $product_id.<br>"; // Debugging output
            return null;  // No product found
        }
    }

    // Method to delete a product by its ID
    public function deleteProduct($product_id) {
        // Prepare the SQL query to delete the product
        $sql = "DELETE FROM products WHERE product_id = ?";
        $stmt = $this->conn->prepare($sql); // Use $this->conn here safely

        if ($stmt) {
            $stmt->bind_param("i", $product_id); // Bind the product ID
            if ($stmt->execute()) {
                return true;
            } else {
                return false; // Return false if deletion failed
            }
        } else {
            return false; // Return false if preparation failed
        }
    }
}
?>