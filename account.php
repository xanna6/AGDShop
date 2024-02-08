<?php 

    session_start(); 
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
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Konto</title>
        <meta content="text/html; charset=UTF-8">
        <style type="text/css">
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            line-height: 1.8em;
        }
        .form_row {
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .form_span {
            display: inline-block;
            text-align: left;
            width: 100px;
            margin-right: 10px;
            font-weight: bold; 
        }
        
        </style>
    </head>

    <body>
        <div style="width:400px; margin:auto">
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
                <div class="form_row">
                    <span class="form_span"> Adres: </span>
                    <span><?PHP echo $row["street"]; ?></span><br/>
                    <span style="margin-left: 115px;"><?PHP echo $row["postal_code"]." ".$row["city"]; ?></span><br/>
                    <span style="margin-left: 115px;"><?PHP echo $row["district"]; ?></span><br/>
                    <span style="margin-left: 115px;"><?PHP echo $row["country"]; ?></span><br/>
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