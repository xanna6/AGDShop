<?php
    session_start();
    require_once "connect.php";

    if(isset($_SESSION['user_id'])) {
        $conn = new mysqli($host, $db_user, $db_password, $db_name);
        if($_SESSION['role'] && $_SESSION['role'] == 'admin') {
            $result = $conn->query("SELECT od.id as order_id, od.delivery_street as street, od.delivery_postal_code as postal_code, od.delivery_city as city, 
                                    od.delivery_district as district, od.delivery_country as country, od.total_price as order_price, od.create_date as create_date,
                                    u.firstname as firstname, u.lastname as lastname
                                    FROM order_data od
                                    JOIN user u ON od.user_id = u.id");
        } else {
            $user_id = $_SESSION['user_id'];
            $result = $conn->query("SELECT od.id as order_id, od.delivery_street as street, od.delivery_postal_code as postal_code, od.delivery_city as city, 
                                    od.delivery_district as district, od.delivery_country as country, od.total_price as order_price, od.create_date as create_date,
                                    u.firstname as firstname, u.lastname as lastname
                                    FROM order_data od
                                    JOIN user u ON od.user_id = u.id
                                    WHERE u.id = $user_id");
        }
        $orders = array();
        while($order = $result->fetch_assoc()) {
            $orders[] = $order;                      
        }
        $conn->close();
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
        <title>Zamówienia</title>
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
                <a <?php if(isset($_SESSION['role']) && $_SESSION['role'] == "admin") {echo 'style="display: none;"'; } ?> href="cart.php">Koszyk<?php 
                    if(isset($_SESSION['cart']) && sizeof($_SESSION['cart']) > 0) {
                        echo " (".sizeof($_SESSION['cart']).")";
                    }?></a>
                <a href="index.php">Produkty</a>
                <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] && $_SESSION['role'] == 'user') {echo '<a href="account.php">Konto</a>'; }?>
                <?php if(isset($_SESSION['user_id'])) {echo '<a href="orders.php" class="active">Zamówienia </a>'; }?>
            </div>
        </div>
        <?php 
            echo '<div>';
            echo '<table style="width:100%; border: 1px solid black; border-collapse: collapse;">';
            echo '<tr>
                    <th style="text-align: left;">Numer zamówienia</th>
                    <th style="text-align: left;">Imię i nazwisko</th>
                    <th style="text-align: left;">Adres dostawy</th>
                    <th style="text-align: left;">Produkty</th>
                    <th style="text-align: left;">Kwota zamówienia</th>
                    <th style="text-align: left;">Data utworzenia</th>
                </tr>';
                foreach($orders as $order) {
                    $price = $order['order_price']/100;
                    $order_id = $order['order_id'];
                    $conn = new mysqli($host, $db_user, $db_password, $db_name);
                    $result = $conn->query("SELECT p.manufacturer as manufacturer, p.serial_number as serial_number, c.name as category_name
                                            FROM product p
                                            JOIN category c ON c.id = p.category
                                            JOIN order_product op ON op.product_id = p.id
                                            JOIN order_data od ON od.id = op.order_id
                                            WHERE od.id = $order_id");
                    $products = array();
                    while($product = $result->fetch_assoc()) {
                        $products[] = $product;                      
                    }
                    $conn->close();
                    echo "<tr id=".$order_id.">";
                    echo '<td style="width: 5%; text-align: left;">'.$order_id."</td>";
                    echo '<td style="width: 10%; text-align: left;">'.$order["firstname"]." ".$order["lastname"]."</td>";
                    echo '<td style="width: 20%; text-align: left;">'.$order["street"]."<br/> ".$order["postal_code"]." ".$order["city"]."<br/>".$order["district"]."<br/>".$order["country"]."</td>";
                    echo '<td style="width: 30%; text-align: left;">';
                    foreach($products as $product) {
                        echo $product["category_name"]." ".$product["manufacturer"]." ".$product["serial_number"]."</br/>";
                    }
                    echo '</td>';
                    echo '<td style="width: 20%; text-align: left;">'.number_format($price, 2, ",", "")." zł</td>";
                    echo '<td style="width: 15%; text-align: left;">'.$order['create_date']."</td>";
                    echo "</tr>";
                }
            echo '</table>';
            echo '</div>';
        ?>
        
    </body>
</html>    