<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_index']) && isset($_POST['new_quantity'])) {
        $productIndex = $_POST['product_index'];
        $newQuantity = (int)$_POST['new_quantity'];

        // Check if the product index exists in the cart
        if (isset($_SESSION['cart'][$productIndex])) {
            // Update the quantity of the product
            $_SESSION['cart'][$productIndex]['quantity'] = max(1, $newQuantity); // Ensure quantity is at least 1
        }
    }

    // You can redirect the user to the cart page or any other page after updating the quantity
    header("Location: ../cart.php");
    exit();
}
?>
