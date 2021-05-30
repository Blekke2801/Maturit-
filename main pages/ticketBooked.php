<?php
require "../utility/Functions.php";
sec_session_start();
$prenotato = false;
if (isset($_POST["prenota"]) && $_POST["prenota"] == "true") {
    if (is_array($_POST["posto"])) {
        $idB = array();
        $i = 0;
        foreach ($_POST["posto"] as $posto) {
            $idB[$i] = prenota($_POST["ID_table"], $posto);
            $i++;
        }
    } else {
        $idB = prenota($_POST["ID_table"], $_POST["posto"]);
    }
    $prenotato = true;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico" />
    <link rel="stylesheet" href="../utility/css/pageBiglietto.css">
    <title>Prenota!</title>
</head>

<body>

<div class="subnav">
        <a class="btn" href="Home.php">Home</a>
    </div>
    <?php
    if ($prenotato) {
        echo "<h1>Pagamento eseguito con successo!</h1>";
        foreach ($idB as $biglietto) {
            echo "<h1>Il tuo biglietto:</h1>";
            $t = biglietti($biglietto);
            $TimeTable = prendi_orari(null, $t[3]);
            $film = take_film_prenota($TimeTable[5]);
            $titolo = $film[1];
            $genere = $film[2];
            $data = $TimeTable[1];
            $ora = $TimeTable[2];
            echo '<div class="biglietto"><h4>' . $titolo . '</h4><h3>Genere: ' . $genere . '</h3><h3>Data: ' . $data . '</h3><h3>Ora: ' . $ora . '</h3><h3>Codice QR:</h3>';
            echo '<img src="../utility/qr.php?id="' . $biglietto . ' /></div>';
        }
    } else {
        if (isset($_GET["id"])) {
            $biglietto = $_GET["id"];
        } else {
            header("Location:Home.php?ste=fisico");
        }
        $t = biglietti($biglietto);
        $TimeTable = prendi_orari(null, $t[3]);
        $film = take_film_prenota($TimeTable["ID_Film"]);
        $titolo = $film[1];
        $genere = $film[2];
        $data = $TimeTable["Data"];
        $ora = $TimeTable["ora"];
        echo '<div class="biglietto"><h4>' . $titolo . '</h4><h3>Genere: ' . $genere . '</h3><h3>Data: ' . $data . '</h3><h3>Ora: ' . $ora . '</h3><h3>Codice QR:</h3>';
        echo '<div class="QRCode"><img src="../utility/qr.php?id="' . $biglietto . ' /></div>';
    }

    ?>
    </div>
</body>

</html>