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
                    <?PHP echo $_POST["firstname"]; ?><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Nazwisko: </span>
                    <?PHP echo $_POST["lastname"]; ?><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Login: </span>
                    <?PHP echo $_POST["username"]; ?><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Hasło: </span>
                    <?PHP echo $_POST["password1"]; ?><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Adres e-mail: </span>
                    <?PHP echo $_POST["email"]; ?><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Adres: </span>
                    <span><?PHP echo $_POST["street"]; ?></span><br/>
                    <span style="margin-left: 115px;"><?PHP echo $_POST["postal_code"]." ".$_POST["city"]; ?></span><br/>
                    <span style="margin-left: 115px;"><?PHP echo $_POST["district"]; ?></span><br/>
                    <span style="margin-left: 115px;"><?PHP echo $_POST["country"]; ?></span><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Wykształcenie: </span>
                    <?PHP echo $_POST["education"]; ?><br/>
                </div>
                <div>
                    <span class="form_span"> Zainteresowania: </span>
                    <?PHP foreach ($_POST["interests"] as $interest) {
                    echo $interest." ";
                    }?>
                </div>
        </div>
        
    </body>
</html>    