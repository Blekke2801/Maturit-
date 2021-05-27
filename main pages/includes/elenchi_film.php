<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (strpos($url, 'Home.php') === false) {

    require "../../utility/connect.php";
    require "../../utility/Functions.php";
    header("Location:../Home.php");
}
if (isset($_GET["ricerca"])) {
    $films = research($_GET["ricerca"]);
} else {
    $films = take_film_stream();
}
?>
<div>
    <?php
    if (isset($_GET["ricerca"])) {
        echo "<h3> Ecco i risultati per la ricerca: " . $_GET["ricerca"] . "</h3><hr>";
    } else {
        echo "<h3> Ecco i Film disponibili nel nostro sito</h3><hr>";
    }
    
    foreach ($films as $single) {
        $percorsoFilm = "../films/stream/" . $single;
        $Titolo = $single;
        $cartella = $Titolo;
        $Titolo[0] = strtoupper($Titolo[0]);
        $img = $percorsoFilm . "/horizontal.jpg";
        $Percorsotrama = $percorsoFilm . "/trama.txt";
        $myfile = fopen($Percorsotrama, "r") or die("Unable to open file!");
        $trama = "";
        for($i = 0;$i < 200;$i++) {
            $trama = $trama . fgetc($myfile);
        }
        fclose($myfile);
        $trama = $trama . "...";
        echo "<a href='Home.php?NomeFilm=$cartella' class='film'><img class='locandinaElenco' src='$img'><p class='tramaIntro'>$trama</p><h1 class='text'>$Titolo</h1></a>";
    }
    ?>
</div>