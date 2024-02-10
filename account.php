<?php 

    session_start(); 
    if(isset($_SESSION['user_id']) && $_SESSION['role'] && $_SESSION['role'] == 'user') {
        $user_id = $_SESSION['user_id'];

        require_once "connect.php";
        $conn = new mysqli($host, $db_user, $db_password, $db_name);
        $result = $conn->query("SELECT user.id, firstname, lastname, username, password, email, street, postal_code, city, district, country, education, GROUP_CONCAT(interests.interest SEPARATOR ', ') AS interest_list 
                                FROM user JOIN interests ON user.id = interests.user_id GROUP BY user.id HAVING user.id = $user_id");
        if($result->num_rows == 0) {
            header('Location: index.php');
        }
        $row = $result->fetch_assoc();
        $conn->close();
    }
    
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Konto</title>
        <meta content="text/html; charset=UTF-8">
        <link href="styles.css" rel="stylesheet" type="text/css">
    </head>

    <body>
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
            <a href="index.php">Produkty</a>
            <?php if(isset($_SESSION['user_id']) && $_SESSION['role'] && $_SESSION['role'] == 'user') {echo '<a href="account.php" class="active">Konto</a>'; }?>
            <?php if(isset($_SESSION['user_id'])) {echo '<a href="orders.php">Zamówienia </a>'; }?>
        </div>
        <div style="width:400px; margin:auto;">
            <h3>Twoje konto</h3>
                <div class="form_row">
                    <span class="form_span"> Imię: </span>
                    <?PHP echo $row["firstname"]; ?><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Nazwisko: </span>
                    <?PHP echo $row["lastname"]; ?><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Login: </span>
                    <?PHP echo $row["username"]; ?><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Hasło: </span>
                    <?PHP echo $row["password"]; ?><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Adres e-mail: </span>
                    <?PHP echo $row["email"]; ?><br/>
                </div>
                <div class="form_row" style="height: auto;">
                    <span class="form_span"> Adres: </span>
                    <span><?PHP echo $row["street"]; ?></span><br/>
                    <span style="margin-left: 215px;"><?PHP echo $row["postal_code"]." ".$row["city"]; ?></span><br/>
                    <span style="margin-left: 215px;"><?PHP echo $row["district"]; ?></span><br/>
                    <span style="margin-left: 215px;"><?PHP echo $row["country"]; ?></span><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Wykształcenie: </span>
                    <?PHP echo $row["education"]; ?><br/>
                </div>
                <div>
                    <span class="form_span"> Zainteresowania: </span>
                    <?PHP echo $row["interest_list"];?>
                </div>
        </div>
        
    </body>
</html>    