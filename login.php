<?php
    session_start();
    require_once "connect.php";


    if(isset($_POST["username"]) && $_POST["password"]) {
        $username = $_POST["username"];
        $password = $_POST["password"];

        $conn = new mysqli($host, $db_user, $db_password, $db_name);
        
        $result = $conn->query("SELECT id FROM user WHERE username='$username' AND password='$password'");
        $number_of_users = $result->num_rows;
        if($number_of_users > 0)
        {
            $row = $result->fetch_assoc();
            $_SESSION['user_id'] = $row['id'];
            header('Location: index.php');
        } else {
            $_SESSION['e_login']="Niepoprawny login lub hasło";
        }	

        $conn->close();
    }
    
?>


<!DOCTYPE html>
<html>
    <head>
        <title>Logowanie</title>
        <meta content="text/html; charset=UTF-8">
        <link href="styles.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <div class="navigation_menu">
            <span class="logo">AGDShop</span>
        </div>
        <div style="width:400px; margin:auto">
            <form method="post">
                <h5>Logowanie</h5>
                <div class="form_row">
                    <span class="form_span"> Login: </span>
                    <input type="text" name="username"/><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Hasło: </span>
                    <input type="password" name="password"/><br/>
                    <?php
                        if (isset($_SESSION['e_login']))
                        {
                            echo '<span class="error">'.$_SESSION['e_login'].'</span>';
                            unset($_SESSION['e_login']);
                        }
                    ?>
                </div>

                <input class="confirm_button" type="submit" value="Zaloguj się"/>
            </form>
        </div>
        
    </body>
</html>    