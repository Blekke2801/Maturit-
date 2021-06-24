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
    <!--pagina di registrazione, controlli dei campi effettuati con js -->
    <div class="register">
        <div class="title">
            <a href="Home.php"><img src="../img/home.png" title="Home"></a>
            <div>
                <h1>Registrati!</h1>
            </div>

        </div>

        <form action="Login.php" method="post" autocomplete="off">
            <div class="" id="error"></div>
            <div class="input">
                <input type="text" class="testo" id="nome" name="nome">
                <label for="nome" class="label">Nome:</label>
            </div>
            <div class="input">
                <input type="text" class="testo" id="cognome" name="cognome">
                <label for="cognome" class="label">Cognome:</label>
            </div>
            <div class="input">
                <input type="mail" class="testo" id="mail" name="mail">
                <label for="mail" class="label">Email:</label>
            </div>
            <div class="input">
                <input type="password" minlength="4" maxlength="12" class="testo" id="pwd" name="pwd">
                <label for="pwd" class="label">Password:</label>
            </div>
            <div class="input">
                <input type="password" class="testo" id="Cpwd" onkeyup="different()">
                <label for="Cpwd" class="label">Conferma password:</label>
            </div>
            <div class="input">
                <input type="date" class="testo" id="birth" name="birth">
                <label for="birth" class="label">Data di nascita:</label>
            </div>
            <label for="tariffa">Piano:</label>
            <div class="tariffa" id="tariffa">
                <div style="padding-right:20px">
                    <input type="radio" id="free" name="tariffa" onclick="show()" value="false" checked hidden>
                    <label for="free"><img src="../img/Free.jpg"></label>
                </div>
                <div>
                    <input type="radio" id="premium" name="tariffa" onclick="show()" value="true" hidden>
                    <label for="premium"><img src="../img/Premium.jpg"></label>
                </div>
            </div>
            <div id="showOffer"><span>Potrai vedere tutti i film che vuoi,<br /> ma in una lista limitata</span></div>
            <button type="button" class="btn" onclick="ceck(this.form)">Registrati!</button>

            <div class="subnav">
                <p>Hai già un account? <a href="Login.php">Login</a></p>
            </div>
        </form>
    </div>

</body>

</html>