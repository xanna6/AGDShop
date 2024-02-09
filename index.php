<?php
    session_start();
    require_once "connect.php";

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
        .navigation_menu {
            width: 100%;
            background-color: gold;
            height: 55px;
        }
        .navigation_menu a {
            float: right;
            text-align: center;
            padding: 15px 10px;
            text-decoration: none;
            font-size: 17px;
            color: black;
            margin: 0px 20px;
        }
        .logo {
            font-size: xx-large;
            font-weight:bold;
            display: inline-block;
            padding: 13px; 
        }
        .active {
            background-color: orange;
        }
        
        </style>
    </head>

    <body>
        <div>
            <div class="navigation_menu">
                <span class="logo">AGDShop</span>
                <a href="cart.php">Koszyk</a>
                <a href="products.php" class="active">Produkty</a>
            </div>
        </div>
        
    </body>
</html>    