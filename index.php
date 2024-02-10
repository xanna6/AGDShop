<?php
    session_start();
    require_once "connect.php";
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if(isset($_POST['delete_product'])) {
        $product_id = $_POST['delete_product'];
        $conn->query("UPDATE product SET deleted = 1 WHERE id = $product_id");
    }

    //pobranie listy produktów z bazy
    $result = $conn->query("SELECT *, product.id as product_id, category.name as category_name FROM product JOIN category ON product.category = category.id WHERE deleted = 0");
    $products = array();
    while($product = $result->fetch_assoc()) {
        $products[] =   $product;                      
    }

    $conn->close();

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

    if(isset($_SESSION['manufacturer'])) {
        unset($_SESSION['manufacturer']);
    }
    if(isset($_SESSION['serial_number'])) {
        unset($_SESSION['serial_number']);
    }
    if(isset($_SESSION['energy_class'])) {
        unset($_SESSION['energy_class']);
    }
    if(isset($_SESSION['price'])) {
        unset($_SESSION['price']);
    }
    if(isset($_SESSION['category'])) {
        unset($_SESSION['category']);
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
            <div class="login_menu">
                <a href="index.php" style="text-decoration: none; float: left; font-size: xxx-large; font-weight: bold;">AGDShop</a>
                <a href="register.php">Zarejestruj się</a>
                <a <?php if(isset($_SESSION['user_id'])) {echo 'style="display: none;"'; }?> href="login.php">Zaloguj się</a>
                <?php if(isset($_SESSION['user_id'])) {echo '<a href="logout.php">Wyloguj się</a>'; }?>
            </div>
            <div class="navigation_menu">
                <a <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin") {echo 'style="display: none;"'; } ?> href="cart.php">Koszyk<?php 
                    if(isset($_SESSION['cart']) && sizeof($_SESSION['cart']) > 0) {
                        echo " (".sizeof($_SESSION['cart']).")";
                    }?></a>
                <a href="index.php" class="active">Produkty</a>
                <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] && $_SESSION['role'] == 'user') {echo '<a href="account.php">Konto</a>'; }?>
                <?php if(isset($_SESSION['user_id'])) {echo '<a href="orders.php">Zamówienia </a>'; }?>
            </div>
        </div>
        <?php 
        if(isset($_SESSION['statement'])) {
            echo '<div style="text-align: center; margin-top: 20px;">';
            echo $_SESSION['statement'];
            echo '</div>';
            unset($_SESSION['statement']);
        }
        if(isset($_SESSION['role']) && $_SESSION['role'] == "admin") {
            echo '<a href="product.php"><button class="add_product_button" name="add_product">Dodaj produkt</button></a>';
            echo '<form id="product_table" action="product.php" method="get"';
            echo '<div class="product_table">';
            echo '<table style="width:100%">';
                foreach($products as $product) {
                    $price = $product['price']/100;
                    $product_id = $product['product_id'];
                    echo "<tr id=".$product_id.">";
                    echo '<td class="product_name">'.$product["category_name"]." ".$product["manufacturer"]." ".$product["serial_number"]."</td>";
                    echo "<td>".number_format($price, 2, ",", "")." zł</td>";
                    echo "<td><button class='delete_product_button' type='submit' name='delete_product' value={$product_id}>Usuń</button></td> ";
                    echo "<td><button class='edit_product_button' type='submit' name='edit_product' value={$product_id}>Edytuj</button></td> ";
                    echo "</tr>";
                }
            echo '</table>';
            echo '</div>';
            echo '</form>';
        } else {
            echo '<form id="product_table" method="post"';
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
        }
        ?>
        
    </body>
</html>    