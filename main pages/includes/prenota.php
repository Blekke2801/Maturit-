<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (strpos($url, 'Home.php') === false) {

    require ("../../utility/Functions.php");
    if (!login_check()) {
        header("Location:../Home.php");
    }
}
echo "<h1 style='color:white'> film in questa settimana </h1>";
$Table = this_week();
foreach ($Table as $row) {
    $single = take_film_prenota($row["ID_Film"])[1];
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
    echo "<a href='Home.php?site=fisico&ID_Film=".$row["ID_Film"]."' class='film'><img class='locandinaElenco' src='$img'><p class='tramaIntro'>$trama</p><h1 class='text'>$Titolo</h1></a>";
}
?>