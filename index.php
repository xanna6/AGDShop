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
            margin-bottom: 20px;
        }
        .form_span {
            display: inline-block;
            text-align: left;
            width: 100px;
            margin-right: 10px;
        }
        
        </style>
    </head>

    <body>
        <div style="width:400px; margin:auto">
            <h3>Rejestracja</h3>
            <form action="register.php" method="post">
                <h5>Dane osobowe</h5>
                <div class="form_row">
                    <span class="form_span"> Imię: </span>
                    <input type="text" name="firstname"/><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Nazwisko: </span>
                        <input type="text" name="lastname"/><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Login: </span>
                        <input class="display: block;  aligned_input" type="text" name="username"/><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Hasło: </span>
                        <input type="password" name="password1"/><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Powtórz hasło: </span>
                        <input type="password" name="password2"/><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Adres e-mail: </span>
                        <input type="text" name="email"/><br/>
                </div>
                <h5>Adres</h5>
                <div class="form_row">
                    <span class="form_span"> Ulica: </span>
                        <input type="text" name="street"/><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Kod-pocztowy: </span>
                        <input type="text" name="postal_code"/><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Miejscowość: </span>
                        <input type="text" name="city"/><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Województwo: </span>
                        <input type="text" name="district"/><br/>
                </div>
                <div class="form_row">
                    <span class="form_span"> Kraj: </span>
                        <input type="text" name="country"/><br/>
                </div>
                <h5>Informacje dodatkowe</h5>
                <div class="form_row">
                    <label style="margin-right: 10px; width: 100px; display: inline-block; text-align: left;" 
                    for="education" accesskey="w">Wykształcenie: </label>
                    <input type="radio" name="education" value="podstawowe" /> podstawowe
                    <input type="radio" name="education" value="srednie" /> średnie
                    <input type="radio" name="education" value="wyzsze" /> wyższe
                </div>
                <div>
                    <label for="interests" accesskey="z">Zainteresowania: </label>
                    <input style="margin-left: 15px;" type="checkbox" name="interests[]" value="sport"/> sport<br/>
                    <input style="margin-left: 120px;" type="checkbox" name="interests[]" value="turystyka"/> turystyka<br/>
                    <input style="margin-left: 120px;" type="checkbox" name="interests[]" value="kino"/> kino<br/>
                    <input style="margin-left: 120px;" type="checkbox" name="interests[]" value="muzyka" /> muzyka<br/>
                    <input style="margin-left: 120px;" type="checkbox" name="interests[]" value="gotowanie" /> gotowanie<br/>
                </div>

                <input style="float:right; margin:20px; background-color:gold; cursor: pointer; border: none;" type="submit" value="Zarejestruj się"/>
            </form>
        </div>
        
    </body>
</html>    