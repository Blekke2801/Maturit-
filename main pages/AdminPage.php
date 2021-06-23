<?php
//pagina riservata agli admin
//l'admin può aggiungere un nuovo film streaming e una nuova tabella oraria gestiti con js
require "../utility/Functions.php";
sec_session_start();
if (!login_check() || $_SESSION["ruolo"] != 0) { //controllo se ha effettuato il login e se è un admin
    header("Location:Home.php");
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin page</title>
    <link rel="icon" href="../img/favicon.ico" />
    <link rel="stylesheet" href="../utility/css/adminPage.css">
</head>

<body>

    <div class="subnav">
        <a class="btn" href="Home.php">Home</a>
    </div>
    <h1>Benvenuto nella pagina degli admin</h1>
    <h2>Che cosa si desidera fare?</h2>
    <div id="labelFilm"><label for="film">Inserisci nuovo film</label><input onchange="operation()" type="radio" name="operation" value="film" id="film"></div>
    <div id="labelTable"><label for="table">Inserisci nuovo orario</label><input onchange="operation()" type="radio" name="operation" id="table" value="table"></div>
    <div id="form"></div>

</body>

</html>
<script>
    var labelFilm = document.getElementById("labelFilm");
    var labeltable = document.getElementById("labelTable");

    function operation() {
        var film = document.getElementById("film");
        var table = document.getElementById("table");
        if (film.checked) {
            labelFilm.style.border = "1px solid green";
            labelTable.style.border = "1px solid #fff";
            form.innerHTML = '<form action="operation.php" method="POST" enctype="multipart/form-data"><fieldset><legend>Nuovo FIlm</legend>Titolo: <input type="text" name="titolo" required>Genere: <input type="text" name="genere" required>Durata: <input type="number" name="durata" min="10" step="1" required><ul><li><input type="radio" name="tariffa" value="free" id="rb1" /><label for="rb1"><img src="../img/Free.jpg" /></label></li><li><input type="radio" name="tariffa" value="premium" id="rb2" /><label for="rb2"><img src="../img/Premium.jpg" /></label></li></ul>Locandina verticale: <input type="file" name="locandinaV" accept="image/jpg" required>Locandina orizzontale: <input type="file" name="locandinaH" accept="image/jpg" required>Trama: <input type="file" name="trama" accept="txt" required>File film: <input type="file" name="film" accept="video/mp4 , video/webm" required><button type="button" onclick="conferma(this.form)" id="button" name="op" value="1">Invia</button></fieldset></form>';
        } else if (table.checked) {
            labelTable.style.border = "1px solid green";
            labelFilm.style.border = "1px solid #fff";
            form.innerHTML = '<form action="operation.php" method="post"><fieldset><legend>Nuovo orario</legend>Data film:<input type="date" name="data" id="data" required>ora film:<input type="time" name="ora" id="ora" required>Sala:<select name="sala"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option></select><br><button type="submit" name="op" value="2">Aggiungi orario!</button></fieldset></form>';
        } else {
            labelFilm.style.border = "1px solid #fff";
            labelTable.style.border = "1px solid #fff";
            form.innerHTML = '';
        }
    }

    function conferma() {
        var free = document.getElementById("rb1");
        var premium = document.getElementById("rb2");
        var inputs = document.getElementsByTagName('input');
        var button = document.getElementById("button");
        var completo = true;
        for (index = 0; index < inputs.length; ++index) {
            if (inputs[index].value == "" || inputs[index].value == null || inputs[index].value == " " && input[index].type != "radio")
                completo = false;
        }
        if (free.checked == false && premium.checked == false) {
            completo = false;
        }
        if (completo) {
            button.innerHTML = "Confermi?";
            setTimeout(function() {
                button.setAttribute("Type", "submit");
            }, 500);
        } else {
            alert("inserire tutti i campi");
        }
    }
</script>