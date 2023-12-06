<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="image\decoration\WaveLookLogo.png">
    <link rel="stylesheet" href="css\shop.css">
    <style>
        .cart-section {
            text-align: center;
            margin: 2rem auto;
            /* Center the cart section and provide some spacing */
            max-width: 800px;
            /* Adjust the maximum width as needed */
        }

        .cart-table {
            width: 100%;
        }

        .quantity-input {
            width: 10%;
            border-radius: .25rem;
        }

        .update-btn,
        .remove-btn {
            border-radius: .25rem;
        }
        .order_button_div{
            width: 100%;
            display: flex;
            justify-content: space-evenly;
        }
        .order_button{
            width: 8rem;
            height: 2.5rem;
            border-radius: .25rem;
        }
        @media screen and (max-width: 600px) {
            .cart-section {
                margin: 2rem 1rem;
                /* Adjust the margin for smaller screens */
            }
        }
    </style>
    <title>Cart</title>
</head>

<body>
    <header>
        <div class="navber">
            <div class="logo">
                <div class="logo-pic">
                    <img src="image\decoration\WaveLookLogo.png" alt="Image Not Found...">
                </div>
                <h2><b class="border">WAVE LOOK</b></h2>
            </div>
            <div class="panal">
                <h4>
                    <p class="border"><a href="index.html">HOME</a></p>
                    <!--<p class="border"><a href="login.html">LOG IN</a></p>-->
                    <p class="border"><a href="about.html">ABOUT</a></p>
                    <p class="border"><a href="shop.html">SHOP</a></p>
                    <p class="border"><a href="contact.html">CONTACT</a></p>
                    <p class="border"><a href="cart.php">CART</a></p>
                </h4>
            </div>
        </div>
    </header>
    <div class="hero-part">
        SHOPING CART
        <div class="under-line">

        </div>
        <div class="shed">

        </div>
    </div>

    <main>
        <section class="cart-section">
            <?php
session_start();

// Check if the cart session variable exists
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    $totalPrice = 0;

    // Calculate total price considering the updated quantity and any discounts
    foreach ($_SESSION['cart'] as $index => $product) {
        $totalPrice += $product['price'] * $_SESSION['cart'][$index]['quantity'];
    }

    // Discount and delivery charge
    $discount = 0; // Set your discount percentage here

    // Default delivery charge
    $deliveryChargeUttara = 70;
    $deliveryChargeOutsideUttara = 115;
    $selectedDeliveryCharge = isset($_POST['delivery_location']) && $_POST['delivery_location'] === 'uttara' ? $deliveryChargeUttara : $deliveryChargeOutsideUttara;
    echo "<tr>
               <form method='post' action=''>
                    <label class='quantity-input' for='delivery_location'>Delivery Location:</label>
                    <select class='quantity-input' id='delivery_location' name='delivery_location'>
                        <option  value='uttara'>Uttara</option>
                        <option value='outside_uttara'>Outside Uttara</option>
                    </select>
                    <button class='quantity-input' type='submit'>Apply</button>
                </form><br><br>";
    // Apply discount
    $discountAmount = ($totalPrice * $discount) / 100;
    $totalPrice -= $discountAmount;

    // Apply delivery charge
    $totalPrice += $selectedDeliveryCharge;

    // Display the table header
    echo "<table class='cart-table'>";
    echo "<thead>
            <tr>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Action</th>
            </tr>
          </thead>";
    echo "<tbody>";

    // Loop through each product in the cart
    foreach ($_SESSION['cart'] as $index => $product) {
        // Display product details in table rows
        echo "<tr>";
        echo "<td>{$product['name']}</td>";
        echo "<td>
                <form method='post' action='php/updateQuantity.php'>
                    <input class='quantity-input' type='number' min='1' name='new_quantity' value='{$product['quantity']}'>
                    <input type='hidden' name='product_index' value='{$index}'>
                    <button class='update-btn' type='submit'>Update</button>
                </form>
              </td>";
        echo "<td>{$product['price']}</td>";
        echo "<td>
                <form method='post' action='php/removeProduct.php'>
                    <input type='hidden' name='product_index' value='{$index}'>
                    <button class='remove-btn' type='submit'>Remove</button>
                </form>
              </td>
              </tr>";
    }

    // Display discount and delivery charge details
    echo "<tr>
            <td colspan='2'></td>
            <td>Discount (-{$discount}%): -$discountAmount</td>
            <td></td>
          </tr>";
    echo "<tr>
            <td colspan='2'></td>
            <td>Delivery Charge: +$selectedDeliveryCharge</td>
            <td></td>
          </tr>
          </tbody>
          <tfoot>
            <tr>
                <td colspan='2'></td>
                <td>Total: $totalPrice</td>
                <td></td>
            </tr>
          </tfoot>";
    echo "</table>";
} else {
    echo "<p class='empty-cart-message'>Your cart is empty.</p>";
}
?>
        </section>

        <!-- Other sections of your page go here -->

    </main>
    <div class="order_button_div">
    <div></div>
    <div>
        <form action="order.html" method="get"> <!-- Use a form for better structure -->
            <button type="submit" class="order_button">Order</button>
        </form>
    </div>
    <div></div>
</div>
    <footer>
        <div class="all-matarial">
            <br>
            &#169; Copy Rights Are Reserved
        </div>
    </footer>
</body>

</html>