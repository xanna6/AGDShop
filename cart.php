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

        if(isset($_SESSION['user_id'])) {
            $user_id = $_SESSION['user_id'];
            $result = $conn->query("SELECT id, street, postal_code, city, district, country, email FROM user WHERE user.id = $user_id");
        }
        $conn->close();

        //do przekierowania z powrotem do koszyka po zalogowaniu
        $_SESSION['last_visited_page'] = "cart";
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
                <span class="logo">AGDShop</span>
                <a href="register.php">Zarejestruj się</a>
                <a <?php if(isset($_SESSION['user_id'])) {echo 'style="display: none;"'; }?> href="login.php">Zaloguj się</a>
                <?php if(isset($_SESSION['user_id'])) {echo '<a href="logout.php">Wyloguj się</a>'; }?>
            </div>
            <div class="navigation_menu">
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

            if(!isset($_SESSION['user_id']) || $_SESSION['user_id'] == null) {
                echo '<div style="text-align: center; margin-top: 100px;">
                Aby złożyć zamówienie, należy się zalogować</br>
                <a href="login.php">Zaloguj się</a>
            </div>';
            } else {
                echo '<div style="width:50%; margin:auto;">
                <form action="order.php" method="post">
                <hr />
                <h3>Dane do dostawy</h3>
                    <div class="form_row">
                        <span class="form_span"> Ulica: </span>
                        <input type="text" ';
                            if (isset($_SESSION['street']))
                            {
                                echo 'value="'.$_SESSION['street'];
                                unset($_SESSION['street']);
                            } 
                            echo '" name="street"/><br/>';
                            if (isset($_SESSION['e_street']))
                            {
                                echo '<span class="error">'.$_SESSION['e_street'].'</span>';
                                unset($_SESSION['e_street']);
                            }
                    echo '</div>
                    <div class="form_row">
                        <span class="form_span"> Kod-pocztowy: </span>
                        <input type="text" '; 
                            if (isset($_SESSION['postal_code']))
                            {
                                echo 'value="'.$_SESSION['postal_code'];
                                unset($_SESSION['postal_code']);
                            } 
                            echo '" name="postal_code"/><br/>';
                            if (isset($_SESSION['e_postal_code']))
                            {
                                echo '<span class="error">'.$_SESSION['e_postal_code'].'</span>';
                                unset($_SESSION['e_postal_code']);
                            }
                    echo '</div>
                    <div class="form_row">
                        <span class="form_span"> Miejscowość: </span>
                        <input type="text" ';
                            if (isset($_SESSION['city']))
                            {
                                echo 'value="'.$_SESSION['city'];
                                unset($_SESSION['city']);
                            } 
                            echo '" name="city"/><br/>';
                            if (isset($_SESSION['e_city']))
                            {
                                echo '<span class="error">'.$_SESSION['e_city'].'</span>';
                                unset($_SESSION['e_city']);
                            }
                    echo '</div>
                    <div class="form_row">
                        <span class="form_span"> Województwo: </span>
                        <input type="text" ';
                            if (isset($_SESSION['district']))
                            {
                                echo 'value="'.$_SESSION['district'];
                                unset($_SESSION['district']);
                            } 
                            echo '" name="district"/><br/>';
                            if (isset($_SESSION['e_district']))
                            {
                                echo '<span class="error">'.$_SESSION['e_district'].'</span>';
                                unset($_SESSION['e_district']);
                            }
                    echo '</div>
                    <div class="form_row">
                        <span class="form_span"> Kraj: </span>
                        <input type="text" ';
                            if (isset($_SESSION['country']))
                            {
                                echo 'value="'.$_SESSION['country'];
                                unset($_SESSION['country']);
                            } 
                            echo '" name="country"/><br/>';
                            if (isset($_SESSION['e_country']))
                            {
                                echo '<span class="error">'.$_SESSION['e_country'].'</span>';
                                unset($_SESSION['e_country']);
                            } 
                    echo '</div>
                    <div class="form_row">
                        <span class="form_span">Adres e-mail: </span>
                        <input type="text" ';
                            if (isset($_SESSION['email']))
                            {
                                echo 'value="'.$_SESSION['email'];
                                unset($_SESSION['email']);
                            } 
                            echo '" name="email"/><br/>';  
                            if (isset($_SESSION['e_email']))
                            {
                                echo '<span class="error">'.$_SESSION['e_email'].'</span>';
                                unset($_SESSION['e_email']);
                            }
                    echo '</div>
                    <div class="form_row">
                        <span class="form_span"> Numer telefonu: </span>
                        <input type="text" ';
                            if (isset($_SESSION['phone_number']))
                            {
                                echo 'value="'.$_SESSION['phone_number'];
                                unset($_SESSION['phone_number']);
                            } 
                            echo '" name="phone_number"/><br/>'; 
                    echo '</div>';
                echo '<input class="confirm_button" type="submit" value="Potwierdź zamówienie"/>';
                echo '</form></div>';
            }

            
        }
        ?>
        
    </body>
</html>    