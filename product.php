<?php
    session_start();
    require_once "connect.php";
    $conn = new mysqli($host, $db_user, $db_password, $db_name);

    if(isset($_GET['edit_product'])) {
        $product_id = $_GET['edit_product'];
        $_SESSION['product_id'] = $product_id;

        if(!isset($_SESSION['manufacturer'])) {
            $product_id = $_SESSION['product_id'];
            $result = $conn->query("SELECT product.id as product_id, category.id as category, manufacturer, serial_number, energy_class, price 
                                    FROM product JOIN category ON product.category = category.id 
                                    WHERE product.id = $product_id");
            $row = $result->fetch_assoc();
            echo $row['energy_class'];
            $_SESSION['product_id'] = $product_id;
            $_SESSION["manufacturer"] = $row['manufacturer'];
            $_SESSION["serial_number"] = $row['serial_number'];
            $_SESSION["energy_class"] = $row['energy_class'];
            $_SESSION["price"] = $row['price']/100;
            $_SESSION["category"] = $row['category'];
        }
    }

    if (isset($_POST['manufacturer'])) {
        $manufacturer = $_POST["manufacturer"];
        $serial_number = $_POST["serial_number"];
        $energy_class = $_POST["energy_class"];
        $price = $_POST["price"];
        $category = $_POST["category"];

        //Walidacja formularza
        $validation_passed=true;
		
        if ((strlen($manufacturer)<3) || (strlen($manufacturer)>30)) {
			$validation_passed=false;
			$_SESSION['e_manufacturer']="Producent musi posiadać od 3 do 30 znaków";
		}
         if ((strlen($serial_number)<3) || (strlen($serial_number)>30)) {
			$validation_passed=false;
			$_SESSION['e_serial_number']="Model musi posiadać od 3 do 30 znaków";
		}
		if ($energy_class == null || $energy_class == "") {
			$validation_passed=false;
			$_SESSION['e_energy_class']="Klasa energetyczna jest wymagana";
		}
		if ($price == null || $price == "") {
			$validation_passed=false;
			$_SESSION['e_price']="Cena jest wymagana";
		}
        if(!isset($_POST['category'])) {
			$validation_passed=false;
			$_SESSION['e_category']="Kategoria jest wymagana";
        } else {
            $_SESSION["category"] = $_POST['category'];
            $category = $_SESSION["category"];
        }
		
        $_SESSION["manufacturer"] = $manufacturer;
        $_SESSION["serial_number"] = $serial_number;
        $_SESSION["energy_class"] = $energy_class;
        $_SESSION["price"] = $price;

        $conn = new mysqli($host, $db_user, $db_password, $db_name);
        
        if($validation_passed) {
            
            $conn->query("UPDATE product SET manufacturer = '$manufacturer', serial_number = '$serial_number', energy_class = '$energy_class', price =".floatval($price*100).", category = $category
                          WHERE id = $product_id");
            $conn->close();

            unset($_SESSION["manufacturer"]);
            unset($_SESSION["serial_number"]);
            unset($_SESSION["energy_class"]);
            unset($_SESSION["price"]);
            unset($_SESSION["category"]);
            unset($_SESSION["product_id"]);
            header('Location: index.php');
        }
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Produkt</title>
        <meta content="text/html; charset=UTF-8">
        <link href="styles.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div class="navigation_menu">
            <span class="logo">AGDShop</span>
        </div>
        <div style="width:500px; margin:auto">
            <h3>Szczegóły produktu</h3>
            <form method="post">
                <div class="form_row">
                    <span class="form_span"> Producent: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['manufacturer']))
                        {
                            echo $_SESSION['manufacturer'];
                            unset($_SESSION['manufacturer']);
                        } 
                        ?>" name="manufacturer"/><br/>
                    <?php
                        if (isset($_SESSION['e_manufacturer']))
                        {
                            echo '<span class="error">'.$_SESSION['e_manufacturer'].'</span>';
                            unset($_SESSION['e_manufacturer']);
                        }
                    ?>
                </div>
                <div class="form_row">
                    <span class="form_span"> Model: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['serial_number']))
                        {
                            echo $_SESSION['serial_number'];
                            unset($_SESSION['serial_number']);
                        } 
                        ?>" name="serial_number"/><br/>
                    <?php
                        if (isset($_SESSION['e_serial_number']))
                        {
                            echo '<span class="error">'.$_SESSION['e_serial_number'].'</span>';
                            unset($_SESSION['e_serial_number']);
                        }
                    ?> 
                    </div>
                <div class="form_row">
                    <label class="form_span" for="category-select">Kategoria:</label>
                    <select name="category" id="category-select" style="width: 177px; ">
                    <option value="" disabled selected hidden>Kategoria</option>
                    <option value="1" <?php if(isset($_SESSION['category']) && $_SESSION['category'] == 1) {echo "selected"; } ?>>Lodówka</option>
                    <option value="2" <?php if(isset($_SESSION['category']) && $_SESSION['category'] == 2) {echo "selected"; } ?>>Pralka</option>
                    <option value="3" <?php if(isset($_SESSION['category']) && $_SESSION['category'] == 3) {echo "selected"; } ?>>Zmywarka</option>
                    <option value="4" <?php if(isset($_SESSION['category']) && $_SESSION['category'] == 4) {echo "selected"; } ?>>Piekarnik</option>
                    </select>
                    <?php
                        if (isset($_SESSION['e_category']))
                        {
                            echo '<span class="error">'.$_SESSION['e_category'].'</span>';
                            unset($_SESSION['e_category']);
                        }
                    ?>
                </div>
                <div class="form_row">
                    <span class="form_span"> Klasa energetyczna: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['energy_class']))
                        {
                            echo $_SESSION['energy_class'];
                            unset($_SESSION['energy_class']);
                        } 
                        ?>" name="energy_class"/><br/>
                    <?php
                        if (isset($_SESSION['e_energy_class']))
                        {
                            echo '<span class="error">'.$_SESSION['e_energy_class'].'</span>';
                            unset($_SESSION['e_energy_class']);
                        }
                    ?>
                </div>
                <div class="form_row">
                    <span class="form_span"> Cena: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['price']))
                        {
                            echo number_format($_SESSION['price'], 2, ".", "");
                            unset($_SESSION['price']);
                        } 
                        ?>" name="price"/><br/>
                        <?php
                        if (isset($_SESSION['e_price']))
                        {
                            echo '<span class="error">'.$_SESSION['e_price'].'</span>';
                            unset($_SESSION['e_price']);
                        }
                    ?>
                </div>

                <input style="float:right; margin:20px; background-color:gold; cursor: pointer; border: none;" type="submit" value="Edytuj produkt"/>
            </form>
        </div>
        
    </body>
</html>    