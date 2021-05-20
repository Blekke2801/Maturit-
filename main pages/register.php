<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/controls.js"></script>
    <script src="../js/sha512.js"></script>
    <link rel="stylesheet" href="../utility/css/register.css">
    <title>ComVid</title>
</head>

<body>
    <form action="Login.php" method="POST" autocomplete="off">
        <div class="" id="error"></div>
        <input type="text" id="nome" name="nome" placeholder="Nome...">
        <input type="text" id="cognome" name="cognome" placeholder="Cognome...">
        <input type="mail" id="mail" name="mail" placeholder="E-mail...">
        <input type="password" id="pwd" name="pwd" placeholder="Password...">
        <input type="password" id="Cpwd" onchange="different()" placeholder="Ripeti password...">
        <input type="date" id="birth" name="birth" placeholder="dd/mm/aaaa">
        <div class="tariffa" id="tariffa">
            <div>
                <input type="radio" name="tariffa" onclick="show()" value="false" checked>
                <span>Free</span>
            </div>
            <div>
                <input type="radio" name="tariffa" onclick="show()" value="true">
                <span>Premium</span>
            </div>
            <div id="showOffer"></div>
        </div>
        <button type="button" name="register" value="1" onclick="ceck(this.form)">Registrati!</button>
    </form>
</body>

</html>