<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
//controllo per vedere se l'utente è entrato in questa pagina senza home
if (strpos($url, 'Home.php') === false) {
    header("Location:../Home.php");
}
echo "<h3> Ecco i Film disponibili nel nostro sito</h3><hr>";
$t = biglietti();
if (sizeof($t) > 0) {
    foreach ($t as $idB) {
        $titolo = $idB["Titolo"];
        $genere = $idB["genere"];
        $data = $idB["Data"];
        $ora = $idB["ora"];
        $posto = $idB["posto"];
        $sala = $idB["sala"];
        $percorsoFilm = "../films/prenota/" . $titolo;
        $img = $percorsoFilm . "/horizontal.jpg";
        echo "<a href='ticketBooked.php?id=" . $idB["ID_Ticket"] . "' class='ticket'><img class='locandinaElenco' src='$img'><div class='infos'><p>Data: $data</p><p>Ora: $ora</p><p>Sala: $sala</p><p>Posto: $posto</p></div></a>";
    }
} else {
    echo "<h4>Nessun risultato trovato</h4>";
}
