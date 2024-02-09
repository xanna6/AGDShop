<?php
    session_start();
    require_once "connect.php";
    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    $result = $conn->query("SELECT *, product.id as product_id, category.name as category_name FROM product JOIN category ON product.category = category.id");
    $products = array();
    while($product = $result->fetch_assoc()) {
        $products[] =   $product;                      
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Produkty</title>
        <meta content="text/html; charset=UTF-8">
        <link href="styles.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div>
            <div class="navigation_menu">
                <span class="logo">AGDShop</span>
                <a href="cart.php">Koszyk</a>
                <a href="products.php" class="active">Produkty</a>
            </div>
        </div>

        <?php 
        echo '<div class="product_table">';
        echo '<table style="width:100%">';
            foreach($products as $product) {
                $price = $product['price']/100;
                $product_id = $product['product_id'];
                echo "<tr id=".$product_id.">";
                echo '<td class="product_name">'.$product["category_name"]." ".$product["manufacturer"]." ".$product["serial_number"]."</td>";
                echo "<td>".number_format($price, 2, ",", "")." zł</td>";
                echo "<td><button class='cart_button' type='submit' name='add_to_cart' value={$product_id}>Dodaj do koszyka</button></td> ";
                echo "</tr>";
            }
        echo "</table>";
        echo "</div>"
        ?>
        
    </body>
</html>    