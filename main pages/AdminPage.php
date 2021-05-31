<?php
//pagina riservata agli admin
//l'admin può aggiungere un nuovo film streaming e una nuova tabella oraria
require "../utility/Functions.php";
sec_session_start();
if (!login_check() || !$_SESSION["ruolo"]) {
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
    <style>
        ul {
            list-style-type: none;
        }

        li {
            display: inline-block;
        }

        input[type="checkbox"][id^="cb"] {
            display: none;
        }

        label {
            border: 1px solid #fff;
            padding: 10px;
            display: block;
            position: relative;
            margin: 10px;
            cursor: pointer;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }

        label::before {
            background-color: white;
            color: white;
            content: " ";
            display: block;
            border-radius: 50%;
            border: 1px solid grey;
            position: absolute;
            top: -5px;
            left: -5px;
            width: 25px;
            height: 25px;
            text-align: center;
            line-height: 28px;
            transition-duration: 0.4s;
            transform: scale(0);
        }

        label img {
            height: 100px;
            width: 100px;
            transition-duration: 0.2s;
            transform-origin: 50% 50%;
        }

        :checked+label {
            border-color: #ddd;
        }

        :checked+label::before {
            content: "✓";
            background-color: grey;
            transform: scale(1);
        }

        :checked+label img {
            transform: scale(0.9);
            box-shadow: 0 0 5px #333;
            z-index: -1;
        }
    </style>
</head>

<body>
    <h1>Benvenuto nella pagina degli admin</h1>
    <h2>Che cosa si desidera fare?</h2>
    <div><label for="film">Inserisci nuovo film</label><input type="radio" name="operation" value="film" id="film"></div>
    <div><label for="table">Inserisci nuovo orario</label><input type="radio" name="operation" id="table" value="table"></div>
    <div id="form"></div>
    
</body>

</html>
<script>
    var film = document.getElementById("film");
    var table = document.getElementById("table");

    function operation() {
        if (film.checked) {
            document.getElementById("form").innerHTML = '<form action="operation.php" method="POST" enctype="multipart/form-data">Titolo: <input type="text" name="titolo">Genere: <input type="text" name="genere">Durata: <input type="number" name="durata" min="10" step="1"><ul><li><input type="radio" name="tariffa" value="free" id="rb1" /><label for="rb1"><img src="../img/Free.jpg" /></label></li><li><input type="radio" name="tariffa" value="premium" id="cb2" /><label for="cb2"><img src="../img/Premium.jpg" /></label></li></ul>Locandina verticale: <input type="file" name="locandinaV" accept="image/jpg">Locandina orizzontale: <input type="file" name="locandinaH" accept="image/jpg">Trama: <input type="file" name="trama" accept="txt">File film: <input type="file" name="film" accept="video/*"><button type="submit" name="op" value="1">Invia</button></form>';
        } else if (table.checked) {
            document.getElementById("form").innerHTML = '<form action="operation.php" method="post"><input type="date" name="data" id=""><input type="time" name="ora" id=""><select name="sala"><option value="1">1</option><option value="2">2</option><option value="2">3</option><option value="2">4</option></select><button type="submit" name="op" value="2">Aggiungi orario!</button></form>';
        } else {
            document.getElementById("form").innerHTML = '';
        }
    }
</script>