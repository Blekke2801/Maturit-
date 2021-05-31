<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (strpos($url, 'Home.php') === false) {
    header("Location:../Home.php");
}
$films = lista();
//mostra i film presenti nella lista dell'utente
echo "<div>";
echo "<h3> Ecco i Film presenti nella tua lista</h3><hr>";
if (sizeof($films) > 0) {
    foreach ($films as $single) {
        $percorsoFilm = "../films/stream/" . $single;
        $Titolo = $single;
        $cartella = $Titolo;
        $Titolo[0] = strtoupper($Titolo[0]);
        $img = $percorsoFilm . "/horizontal.jpg";
        $Percorsotrama = $percorsoFilm . "/trama.txt";
        $myfile = fopen($Percorsotrama, "r") or die("Unable to open file!");
        $trama = "";
        for ($i = 0; $i < 200; $i++) {
            $trama = $trama . fgetc($myfile);
        }
        fclose($myfile);
        $trama = $trama . "...";
        echo "<a href='Home.php?NomeFilm=$cartella' class='film'><img class='locandinaElenco' src='$img'><p class='tramaIntro'>$trama</p><h1 class='text'>$Titolo</h1></a>";
    }
} else {
    echo "<h4>Nessun film presente nella tua lista</h4>";
}
?>
</div>