<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="../js/controls.js"></script>
    <script src="../js/sha512.js"></script>
    <script src="../js/features.js"></script>
    <link rel="icon" href="../img/favicon.ico" />
    <link rel="stylesheet" href="../utility/css/register.css">
    <title>ComVid</title>
</head>

<body>
    <div class="register">
        <h1 style="text-align:center">Registrati!</h1>
        <form action="Login.php" method="POST" autocomplete="off">
            <div class="" id="error"></div>
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" placeholder="Nome...">
            <label for="cognome">Cognome:</label>
            <input type="text" id="cognome" name="cognome" placeholder="Cognome...">
            <label for="mail">Email:</label>
            <input type="mail" id="mail" name="mail" placeholder="E-mail...">
            <label for="pwd">Password:</label>
            <input type="password" id="pwd" name="pwd" placeholder="Password...">
            <label for="Cpwd">Conferma password:</label>
            <input type="password" id="Cpwd" onchange="different()" placeholder="Ripeti password...">
            <label for="birth">Data di nascita:</label>
            <input type="date" id="birth" name="birth" placeholder="dd/mm/aaaa">
            <label for="tariffa">Piano:</label>
            <div class="tariffa" id="tariffa">
                <div>
                    <input type="radio" id="free" name="tariffa" onclick="show()" value="false" checked>
                    <span>Free</span>
                </div>
                <div>
                    <input type="radio" id="premium" name="tariffa" onclick="show()" value="true">
                    <span>Premium</span>
                </div>
                <div id="showOffer"><img src="../img/Free.jpg"><br><span>Potrai vedere tutti i film che vuoi,<br/> ma in una lista limitata</span></div>
            </div>
            <button type="button" name="register" value="1" onclick="ceck(this.form)">Registrati!</button>
        </form>
    </div>

</body>

</html>