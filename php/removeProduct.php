<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['product_index'])) {
        $productIndex = $_POST['product_index'];

        // Check if the product index exists in the cart
        if (isset($_SESSION['cart'][$productIndex])) {
            // Remove the product from the cart
            unset($_SESSION['cart'][$productIndex]);

            // Re-index the array to prevent gaps in the index
            $_SESSION['cart'] = array_values($_SESSION['cart']);
        }
    }

    // You can redirect the user to the cart page or any other page after removing the product
    header("Location: ../cart.php");
    exit();
}
?>
