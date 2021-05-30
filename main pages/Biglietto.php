<?php
require "../utility/Functions.php";
sec_session_start();
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
        if (isset($_POST["NomeFilm"])) {
            $film = take_film_prenota($_POST["NomeFilm"]);
            $titolo = $film["Titolo"];
            $titolo[0] = strtoupper($titolo[0]);
            $TimeDate = explode(" ", $_POST["DateTime"]);
            $Biglietti = Prenotato($film["ID_Film"], $TimeDate[0], $TimeDate[1]);
            $fila = array("A", "B", "C", "D", "E");
            echo "<div class='film_scelto'>";
            echo "<p>Film scelto: " . $titolo . "</p>";
            echo "<p>Genere: " . $film["Genere"] . "</p>";
            echo "<p>Durata: " . $film["durata"] . "</p>";
            echo "</div>";
            echo '<form action="ticketBooked.php" method="post">';
            echo "<input type='radio' name='NomeFilm' value='".$film["Titolo"]."' checked hidden>
            <input type='radio' name='idF' value='".$film["ID_Film"]."' checked hidden>";
            echo "<div class='posti'>";
            echo "<h2>Seleziona i posti che vuoi prenotare</h1>";
            for ($i = 0; $i < 5; $i++) {
                echo "<div class='row'><h4>" . $fila[$i] . "</h4>";
                for ($posto = 1; $posto <= 10; $posto++) {
                    if (array_search($fila[$i] . "-$posto", $Biglietti, true) !== false) {
                        $classeP = "alreadyBooked";
                    } else {
                        $classeP = "free";
                    }
                    echo "<div class='seat'><input name='posto' id='check$i-$posto' type='checkbox' value='" . $fila[$i] . "-" . $posto . "'><label for='check$i-$posto' class='selectSeat $classeP'><b>$posto</b></label></div>";
                }
                echo "</div><br>";
            }
            echo "</div>";
            $prezzoTot = intval($film["prezzo_a_persona"]) * intval($_POST["numero"]);
            echo "Totale: $prezzoTot";
        ?>
            <select name="metodo">
                <option value="presenza">Pagamento in presenza</option>
            </select>
            <button type='submit'>Prenota ora!</button>
            
        </form>
        <?php
        } else {
            header("Location: Home.php?site=fisico");
        } ?>
    </div>
</body>

</html>