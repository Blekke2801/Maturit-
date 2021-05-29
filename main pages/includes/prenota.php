<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (strpos($url, 'Home.php') === false) {

    require ("../../utility/Functions.php");
    if (!login_check()) {
        header("Location:../Home.php");
    }
}
echo "<h1 style='color:white'> film in questa settimana </h1>";
$films = take_film_prenota();
foreach ($films as $single) {
    $percorsoFilm = "../films/prenota/" . $single;
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
    echo "<a href='Home.php?site=fisico&NomeFilm=$cartella' class='film'><img class='locandinaElenco' src='$img'><p class='tramaIntro'>$trama</p><h1 class='text'>$Titolo</h1></a>";
}
?>