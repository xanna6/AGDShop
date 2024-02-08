<?php
    session_start();
    require_once "connect.php";

    if (isset($_POST['email'])) {
        $firstname = $_POST["firstname"];
        $lastname = $_POST["lastname"];
        $username = $_POST["username"];
        $password1 = $_POST["password1"];
        $password2 = $_POST["password2"];
        $email = $_POST["email"];
        $street = $_POST["street"];
        $postal_code = $_POST["postal_code"];
        $city = $_POST["city"];
        $district = $_POST["district"];
        $country = $_POST["country"];

        //Walidacja formularza
        $validation_passed=true;
		
        if ((strlen($firstname)<3) || (strlen($firstname)>30)) {
			$validation_passed=false;
			$_SESSION['e_firstname']="Imię musi posiadać od 3 do 30 znaków";
		}
         if ((strlen($lastname)<3) || (strlen($lastname)>30)) {
			$validation_passed=false;
			$_SESSION['e_lastname']="Nazwisko musi posiadać od 3 do 30 znaków";
		}
		if ((strlen($username)<3) || (strlen($username)>30)) {
			$validation_passed=false;
			$_SESSION['e_username']="Login musi posiadać od 3 do 30 znaków";
		}
		if (ctype_alnum($username)==false) {
			$validation_passed=false;
			$_SESSION['e_username']="Login może składać się tylko z liter i cyfr";
		}
		if ((strlen($password1)<8) || (strlen($password1)>30)) {
			$validation_passed=false;
			$_SESSION['e_password']="Hasło musi posiadać od 8 do 30 znaków";
		}
		if ($password1!=$password2) {
			$validation_passed=false;
			$_SESSION['e_password']="Podane hasła nie są identyczne!";
		}	
		
        //usunięcie z maila niedozwolonych znaków
		$emailB = filter_var($email, FILTER_SANITIZE_EMAIL);
		
        //sprawdzenie, czy podany email jest poprawnym dresem e-mail
		if ((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email)) {
			$validation_passed=false;
			$_SESSION['e_email']="Podaj poprawny adres e-mail";
		}
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
		if (!isset($_POST['education']))
		{
			$validation_passed=false;
			$_SESSION['e_education']="Wybierz poziom wykształcenia spośród podanych";
		} else {
            $education = $_POST["education"];
            $_SESSION["education"] = $education; 
        }		
        if (!isset($_POST['interests']))
		{
			$validation_passed=false;
			$_SESSION['e_interests']="Wybierz zainteresowania spośród podanych";
		} else {
            $interests = $_POST["interests"];
            $_SESSION["interests"] = $interests;
        }

        $_SESSION["firstname"] = $firstname;
        $_SESSION["lastname"] = $lastname;
        $_SESSION["username"] = $username;
        $_SESSION["password1"] = $password1;
        $_SESSION["password2"] = $password2;
        $_SESSION["email"] = $email;
        $_SESSION["street"] = $street;
        $_SESSION["postal_code"] = $postal_code;
        $_SESSION["city"] = $city;
        $_SESSION["district"] = $district;
        $_SESSION["country"] = $country;

        $conn = new mysqli($host, $db_user, $db_password, $db_name);
        
        //sprawdzenie, czy adres e-mail jest już w bazie
        $result = $conn->query("SELECT id FROM user WHERE email='$email'");
        $number_of_mails = $result->num_rows;
        if($number_of_mails>0)
        {
            $validation_passed = false;
            $_SESSION['e_email']="Istnieje już konto przypisane do tego adresu e-mail";
        }		

        //sprawdzenie, czy login jest już w bazie
        $result = $conn->query("SELECT id FROM user WHERE username='$username'");
        $number_of_usernames = $result->num_rows;
        if($number_of_usernames>0)
        {
            $validation_passed = false;
            $_SESSION['e_username']="Wybrany login jest zajęty";
        }

        if($validation_passed) {
            
            $conn->query("INSERT INTO user VALUES (NULL, '$firstname', '$lastname', '$username', '$password1', '$email', '$street', '$postal_code', '$city', '$district', '$country', '$education')");
            $last_id=$conn->insert_id;
            foreach($interests as $interest){
                $conn->query("INSERT INTO interests VALUES (NULL, $last_id, '$interest')");  
            }
            $conn->close();
            $_SESSION['user_id'] = $last_id;
            header('Location: account.php');
        }
    }
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Rejestracja</title>
        <meta content="text/html; charset=UTF-8">
        <style type="text/css">
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.8em;
        }
        .form_row {
            margin-top: 10px;
            height: 40px;

        }
        .form_span {
            display: inline-block;
            text-align: left;
            width: 100px;
            margin-right: 10px;
        }
        .error {
            color: red;
            margin-left: 115px;
            font-size: 8pt;
        }
        
        </style>
    </head>

    <body>
        <div style="width:400px; margin:auto">
            <h3>Rejestracja</h3>
            <form method="post">
                <h5>Dane osobowe</h5>
                <div class="form_row">
                    <span class="form_span"> Imię: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['firstname']))
                        {
                            echo $_SESSION['firstname'];
                            unset($_SESSION['firstname']);
                        } 
                        ?>" name="firstname"/><br/>
                    <?php
                        if (isset($_SESSION['e_firstname']))
                        {
                            echo '<span class="error">'.$_SESSION['e_firstname'].'</span>';
                            unset($_SESSION['e_firstname']);
                        }
                    ?>
                </div>
                <div class="form_row">
                    <span class="form_span"> Nazwisko: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['lastname']))
                        {
                            echo $_SESSION['lastname'];
                            unset($_SESSION['lastname']);
                        } 
                        ?>" name="lastname"/><br/>
                    <?php
                        if (isset($_SESSION['e_lastname']))
                        {
                            echo '<span class="error">'.$_SESSION['e_lastname'].'</span>';
                            unset($_SESSION['e_lastname']);
                        }
                    ?> 
                    </div>
                <div class="form_row">
                    <span class="form_span"> Login: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['username']))
                        {
                            echo $_SESSION['username'];
                            unset($_SESSION['username']);
                        } 
                        ?>" name="username"/><br/>
                    <?php
                        if (isset($_SESSION['e_username']))
                        {
                            echo '<span class="error">'.$_SESSION['e_username'].'</span>';
                            unset($_SESSION['e_username']);
                        }
                    ?>
                </div>
                <div class="form_row">
                    <span class="form_span"> Hasło: </span>
                    <input type="password" value="<?php
                        if (isset($_SESSION['password1']))
                        {
                            echo $_SESSION['password1'];
                            unset($_SESSION['password1']);
                        } 
                        ?>" name="password1"/><br/>
                    <?php
                        if (isset($_SESSION['e_password']))
                        {
                            echo '<span class="error">'.$_SESSION['e_password'].'</span>';
                            unset($_SESSION['e_password']);
                        }
                    ?>
                </div>
                <div class="form_row">
                    <span class="form_span"> Powtórz hasło: </span>
                    <input type="password" value="<?php
                        if (isset($_SESSION['password2']))
                        {
                            echo $_SESSION['password2'];
                            unset($_SESSION['password2']);
                        } 
                        ?>" name="password2"/><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Adres e-mail: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['email']))
                        {
                            echo $_SESSION['email'];
                            unset($_SESSION['email']);
                        } 
                        ?>" name="email"/><br/>
                    <?php
                        if (isset($_SESSION['e_email']))
                        {
                            echo '<span class="error">'.$_SESSION['e_email'].'</span>';
                            unset($_SESSION['e_email']);
                        }
                    ?>
                </div>
                <h5>Adres</h5>
                <div class="form_row">
                    <span class="form_span"> Ulica: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['street']))
                        {
                            echo $_SESSION['street'];
                            unset($_SESSION['street']);
                        } 
                        ?>" name="street"/><br/>
                    <?php
                        if (isset($_SESSION['e_street']))
                        {
                            echo '<span class="error">'.$_SESSION['e_street'].'</span>';
                            unset($_SESSION['e_street']);
                        }
                    ?>
                </div>
                <div class="form_row">
                    <span class="form_span"> Kod-pocztowy: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['postal_code']))
                        {
                            echo $_SESSION['postal_code'];
                            unset($_SESSION['postal_code']);
                        } 
                        ?>" name="postal_code"/><br/>
                    <?php
                        if (isset($_SESSION['e_postal_code']))
                        {
                            echo '<span class="error">'.$_SESSION['e_postal_code'].'</span>';
                            unset($_SESSION['e_postal_code']);
                        }
                    ?>
                </div>
                <div class="form_row">
                    <span class="form_span"> Miejscowość: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['city']))
                        {
                            echo $_SESSION['city'];
                            unset($_SESSION['city']);
                        } 
                        ?>" name="city"/><br/>
                    <?php
                        if (isset($_SESSION['e_city']))
                        {
                            echo '<span class="error">'.$_SESSION['e_city'].'</span>';
                            unset($_SESSION['e_city']);
                        }
                    ?>
                </div>
                <div class="form_row">
                    <span class="form_span"> Województwo: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['district']))
                        {
                            echo $_SESSION['district'];
                            unset($_SESSION['district']);
                        } 
                        ?>" name="district"/><br/>
                    <?php
                        if (isset($_SESSION['e_district']))
                        {
                            echo '<span class="error">'.$_SESSION['e_district'].'</span>';
                            unset($_SESSION['e_district']);
                        }
                    ?>
                </div>
                <div class="form_row">
                    <span class="form_span"> Kraj: </span>
                    <input type="text" value="<?php
                        if (isset($_SESSION['country']))
                        {
                            echo $_SESSION['country'];
                            unset($_SESSION['country']);
                        } 
                        ?>" name="country"/><br/>
                    <?php
                        if (isset($_SESSION['e_country']))
                        {
                            echo '<span class="error">'.$_SESSION['e_country'].'</span>';
                            unset($_SESSION['e_country']);
                        }
                    ?>
                </div>
                <h5>Informacje dodatkowe</h5>
                <div class="form_row">
                    <label style="margin-right: 10px; width: 100px; display: inline-block; text-align: left;" 
                    for="education" accesskey="w">Wykształcenie: </label>
                    <input type="radio" name="education" value="podstawowe" <?php echo (isset($_SESSION["education"]) && $_SESSION["education"] == "podstawowe") ? 'checked="checked"':''; ?>/> podstawowe
                    <input type="radio" name="education" value="średnie" <?php echo (isset($_SESSION["education"]) && $_SESSION["education"] == "średnie") ? 'checked="checked"':''; ?>/> średnie
                    <input type="radio" name="education" value="wyższe" <?php echo (isset($_SESSION["education"]) && $_SESSION["education"] == "wyższe") ? 'checked="checked"':''; ?>/> wyższe
                    <?php
                        unset($_SESSION["education"]);
                        if (isset($_SESSION['e_education']))
                        {
                            echo '<span class="error">'.$_SESSION['e_education'].'</span>';
                            unset($_SESSION['e_education']);
                        }
                    ?>
                </div>
                <div style="margin-top: 10px;">
                    <label for="interests" accesskey="z">Zainteresowania: </label>
                    <input style="margin-left: 15px;" type="checkbox" name="interests[]" value="sport" 
                        <?php echo (isset($_SESSION["interests"]) && in_array("sport", $_SESSION["interests"])) ? 'checked="checked"':'';  ?>/> sport<br/>
                    <input style="margin-left: 120px;" type="checkbox" name="interests[]" value="turystyka"
                        <?php echo (isset($_SESSION["interests"]) && in_array("turystyka", $_SESSION["interests"])) ? 'checked="checked"':''; ?>/> turystyka<br/>
                    <input style="margin-left: 120px;" type="checkbox" name="interests[]" value="kino"
                        <?php echo (isset($_SESSION["interests"]) && in_array("kino", $_SESSION["interests"])) ? 'checked="checked"':''; ?>/> kino<br/>
                    <input style="margin-left: 120px;" type="checkbox" name="interests[]" value="muzyka" 
                        <?php echo (isset($_SESSION["interests"]) && in_array("muzyka", $_SESSION["interests"])) ? 'checked="checked"':''; ?>/> muzyka<br/>
                    <input style="margin-left: 120px;" type="checkbox" name="interests[]" value="gotowanie" 
                        <?php echo (isset($_SESSION["interests"]) && in_array("gotowanie", $_SESSION["interests"])) ? 'checked="checked"':''; ?>/> gotowanie<br/>
                    <?php
                        unset($_SESSION['interests']);
                        if (isset($_SESSION['e_interests']))
                        {
                            echo '<span class="error">'.$_SESSION['e_interests'].'</span>';
                            unset($_SESSION['e_interests']);
                        }
                    ?>
                </div>

                <input style="float:right; margin:20px; background-color:gold; cursor: pointer; border: none;" type="submit" value="Zarejestruj się"/>
            </form>
        </div>
        
    </body>
</html>    