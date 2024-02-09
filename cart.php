<?php
    session_start();
    require_once "connect.php";

    if(isset($_POST['remove_from_cart'])) {
        $index = array_search($_POST['remove_from_cart'], $_SESSION['cart']);
        if ($index !== false) {
            unset($_SESSION['cart'][$index]); 
        }
    }

    //pobranie produktów z koszyka z bazy
    if(isset($_SESSION['cart'])) {
        $conn = new mysqli($host, $db_user, $db_password, $db_name);
        $ids = "";
        foreach($_SESSION['cart'] as $product_id) {
            $ids = $ids.$product_id.", ";
        }
        $ids = $ids."-1";
        $result = $conn->query("SELECT *, product.id as product_id, category.name as category_name 
                                FROM product JOIN category ON product.category = category.id 
                                WHERE product.id IN ($ids)");
        $products = array();
        while($product = $result->fetch_assoc()) {
            $products[] = $product;                      
        }
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
                <a class="active" href="cart.php">Koszyk<?php 
                    if(isset($_SESSION['cart']) && sizeof($_SESSION['cart']) > 0) {
                        echo " (".sizeof($_SESSION['cart']).")";
                    }?></a>
                <a href="index.php">Produkty</a>
            </div>
        </div>

        <?php 
        if(sizeof($products) == 0) {
            echo '<div style="text-align: center; margin-top: 100px;">Twój koszyk jest pusty</div>';
        } else {
            echo '<form method="post"';
            echo '<div class="product_table">';
            echo '<table style="width:100%">';
                $sum = 0;
                foreach($products as $product) {
                    $price = $product['price']/100;
                    $sum += $price;
                    $product_id = $product['product_id'];
                    echo "<tr id=".$product_id.">";
                    echo '<td class="product_name">'.$product["category_name"]." ".$product["manufacturer"]." ".$product["serial_number"]."</td>";
                    echo "<td>".number_format($price, 2, ",", "")." zł</td>";
                    echo "<td><button class='remove_from_cart_button' type='submit' name='remove_from_cart' value={$product_id}>Usuń z koszyka</button></td> ";
                    echo "</tr>";
                }
            echo '<tr id="cart_sum">';
            echo '<td class="product_name" style="font-weight: bold;">Suma: </td>';
            echo '<td style="font-weight: bold;">'.number_format($sum, 2, ",", "").' zł</td>';
            echo '</tr>';
            echo '</table>';
            echo '</div>';
            echo '</form>';
        }
        ?>
        
    </body>
</html>    