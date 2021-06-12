<?php
require "../utility/Functions.php";
sec_session_start();
//pagina in cui seleziono il posto da prenotare
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="../img/favicon.ico" />
    <link rel="stylesheet" href="../utility/css/prenota.css">
    <script src='http://ajax.aspnetcdn.com/ajax/jQuery/jquery-3.2.1.js'></script>
    <script src="../js/fisico.js"></script>
    <title>Prenota!</title>
</head>

<body>
    <div class="subnav">
        <a class="btn" href="Home.php">Home</a>
    </div>
    <div class="prenota">
        <?php
        if (isset($_POST["ID_Film"])) {
            $film = take_film_prenota($_POST["ID_Film"]);
            $titolo = $film[1]; //dati del film e della tabella oraria
            $titolo[0] = strtoupper($titolo[0]);
            $TimeTable = prendi_orari($film[0], $_POST["TimeTable"]);
            $Biglietti = Prenotato($_POST["TimeTable"]);
            $fila = array("A", "B", "C", "D", "E");
            echo "<div class='film_scelto'>";
            echo "<p>Film scelto: " . $titolo . "</p>";
            echo "<p>Genere: " . $film[2] . "</p>";
            echo "<p>Durata: " . $film[3] . "</p>";
            echo "</div>";
            echo '<form action="ticketBooked.php" method="post">';
            echo "<input type='radio' name='ID_Film' value='" . $_POST["ID_Film"] . "' checked hidden>";
            echo "<input type='radio' name='ID_table' value='" . $_POST["TimeTable"] . "' checked hidden>";
            echo "<div class='posti'>";
            echo "<h2>Seleziona i posti che vuoi prenotare</h1>";
            for ($i = 0; $i < 5; $i++) {
                echo "<div class='row'><h4>" . $fila[$i] . "</h4>";
                for ($posto = 1; $posto <= 10; $posto++) {
                    if ($Biglietti != null) {
                        if (array_search($fila[$i] . "-$posto", $Biglietti, true) !== false) {
                            $classeP = "alreadyBooked";
                            $disabled = "disabled";
                        } else {
                            $classeP = "free";
                            $disabled = "";
                        }
                    } else {
                        $classeP = "free";
                        $disabled = "";
                    }
                    echo "<div class='seat'><input name='posto[]' id='check$i-$posto' type='checkbox' $disabled value='" . $fila[$i] . "-" . $posto . "'><label for='check$i-$posto' class='selectSeat $classeP'><b>$posto</b></label></div>";
                }
                echo "</div><br>";
            }
            echo "</div>";
            $prezzoTot = floatval($film[4]) * floatval($_POST["numero"]);
            echo "Totale: $prezzoTot";
        ?>
            <select name="metodo">
                <option value="presenza">Pagamento in presenza</option>
            </select>
            <button type='submit' name="prenota" value="true">Prenota ora!</button>

            </form>
        <?php
        } else {
            header("Location: Home.php?site=fisico");
        } ?>
    </div>
</body>

</html>