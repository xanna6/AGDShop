<?php
    session_start();
    require_once "connect.php";

    //pobranie listy produktów z bazy
    $conn = new mysqli($host, $db_user, $db_password, $db_name);
    $result = $conn->query("SELECT *, product.id as product_id, category.name as category_name FROM product JOIN category ON product.category = category.id");
    $products = array();
    while($product = $result->fetch_assoc()) {
        $products[] =   $product;                      
    }

    //dodawanie produktów do koszyka
    if(!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }

    if(isset($_POST['add_to_cart'])) {
        array_push($_SESSION['cart'], $_POST['add_to_cart']);
    }

    if(isset($_POST['remove_from_cart'])) {
        $index = array_search($_POST['remove_from_cart'], $_SESSION['cart']);
        if ($index !== false) {
            unset($_SESSION['cart'][$index]); 
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
                <a href="cart.php">Koszyk<?php 
                    if(isset($_SESSION['cart']) && sizeof($_SESSION['cart']) > 0) {
                        echo " (".sizeof($_SESSION['cart']).")";
                    }?></a>
                <a href="products.php" class="active">Produkty</a>
            </div>
        </div>

        <?php 
        echo '<form method="post"';
        echo '<div class="product_table">';
        echo '<table style="width:100%">';
            foreach($products as $product) {
                $price = $product['price']/100;
                $product_id = $product['product_id'];
                echo "<tr id=".$product_id.">";
                echo '<td class="product_name">'.$product["category_name"]." ".$product["manufacturer"]." ".$product["serial_number"]."</td>";
                echo "<td>".number_format($price, 2, ",", "")." zł</td>";
                if(isset($_SESSION['cart']) && in_array($product_id, $_SESSION['cart'])) {
                    echo "<td><button class='remove_from_cart_button' type='submit' name='remove_from_cart' value={$product_id}>Usuń z koszyka</button></td> ";
                } else {
                    echo "<td><button class='add_to_cart_button' type='submit' name='add_to_cart' value={$product_id}>Dodaj do koszyka</button></td> ";
                }
                echo "</tr>";
            }
        echo '</table>';
        echo '</div>';
        echo '</form>';
        ?>
        
    </body>
</html>    