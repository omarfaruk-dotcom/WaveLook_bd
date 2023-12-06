<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['addToCart'])) {
        $productName = $_POST['product_name'];
        $productPrice = $_POST['product_price'];

        // Check if the cart is already initialized in the session
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = array();
        }

        // Check if the product is already in the cart
        $productIndex = array_search($productName, array_column($_SESSION['cart'], 'name'));

        if ($productIndex !== false) {
            // Product already in the cart, update the quantity
            $_SESSION['cart'][$productIndex]['quantity'] += 1;
        } else {
            // Product not in the cart, add it with quantity 1
            $product = array(
                'name' => $productName,
                'price' => $productPrice,
                'quantity' => 1, // Set the quantity to 1 when adding a new product
            );

            $_SESSION['cart'][] = $product;
        }

        // You can redirect the user to the cart page or any other page after adding to the cart
        header("Location: ../shop.html");
        exit();
    }
}
?>
