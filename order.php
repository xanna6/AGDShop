<?php
    session_start();
    require_once "connect.php";

    if(isset($_POST['street'])) {
        $street = $_POST["street"];
        $postal_code = $_POST["postal_code"];
        $city = $_POST["city"];
        $district = $_POST["district"];
        $country = $_POST["country"];
        $phone_number = $_POST["phone_number"];
        $email = $_POST['email'];
        $user_id = $_SESSION['user_id'];

        $validation_passed=true;

        if ((strlen($street)<3) || (strlen($street)>30)) {
            $validation_passed=false;
            $_SESSION['e_street']="Ulica musi posiadać od 3 do 50 znaków";
        }
        if (!strlen($postal_code)==6) {
            $validation_passed=false;
            $_SESSION['e_postal_code']="Kod pocztowy musi posiadać 6 znaków";
        }
        if ((strlen($city)<3) || (strlen($city)>30)) {
            $validation_passed=false;
            $_SESSION['e_city']="Miasto musi posiadać od 3 do 30 znaków";
        }
        if ((strlen($district)<3) || (strlen($district)>30)) {
            $validation_passed=false;
            $_SESSION['e_district']="Województwo musi posiadać od 3 do 30 znaków";
        }
        if ((strlen($country)<3) || (strlen($country)>30)) {
            $validation_passed=false;
            $_SESSION['e_country']="Kraj musi posiadać od 3 do 30 znaków";
        }
        //usunięcie z maila niedozwolonych znaków
        $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
        
        //sprawdzenie, czy podany email jest poprawnym dresem e-mail
        if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email)) {
            $validation_passed=false;
            $_SESSION['e_email']="Podaj poprawny adres e-mail";
        }

        $_SESSION["street"] = $street;
        $_SESSION["postal_code"] = $postal_code;
        $_SESSION["city"] = $city;
        $_SESSION["district"] = $district;
        $_SESSION["country"] = $country;
        $_SESSION["phone_number"] = $phone_number;
        $_SESSION["email"] = $email;

        if (!$validation_passed) {
            header('Location: cart.php');
        } else {
            $order_price = $_SESSION['order_price']*100;
            $conn = new mysqli($host, $db_user, $db_password, $db_name);
            $conn->query("INSERT INTO order_data VALUES (NULL, '$phone_number', '$street', '$postal_code', '$city', '$district', '$country', $user_id, now(), $order_price)");
            $last_id=$conn->insert_id;
            foreach($_SESSION['cart'] as $product_id){
                $conn->query("INSERT INTO order_product VALUES (NULL, $last_id, '$product_id')");  
            }
            $conn->close();
            unset($_SESSION['cart']);
            unset($_SESSION['street']);
            unset($_SESSION['postal_code']);
            unset($_SESSION['city']);
            unset($_SESSION['district']);
            unset($_SESSION['country']);
            unset($_SESSION['email']);
            unset($_SESSION['phone_number']);
            unset($_SESSION['e_street']);
            unset($_SESSION['e_postal_code']);
            unset($_SESSION['e_city']);
            unset($_SESSION['e_district']);
            unset($_SESSION['e_country']);
            unset($_SESSION['e_email']);
            unset($_SESSION['e_phone_number']);
            unset($_SESSION['order_price']);
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
            </div>
            <div style="text-align: center; margin-top: 100px;">
                Dziękujemy za złożenie zamówienia</br>
                <a href="index.php">Strona główna</a>
            </div>
        </div>
        
    </body>
</html>    