<?php
if (isset($_GET["NomeFilm"])) {
    $nome = $_GET["NomeFilm"];
    if (!isset($_GET["site"]) || $_GET["site"] != "fisico") {
        $Dati = take_film_stream($nome);
    } else {
        Header("Location:./Home.php");
    }
    if (sizeof($Dati) < 1) {
        Header("Location:Home.php");
    } else {
        $percorsoFilm = "../films/stream/" . $nome;
    }
} else {
    Header("Location:./Home.php");
}
//titolo,riga,prenota/guarda se loggato,locandina,riga,genere,trama
?>
<div>
    <?php
    $disabled = false;
    $nome[0] = strtoupper($nome[0]);
    echo "<h1>$nome</h1><hr>";
    echo "<div class='filmRow1'><img src='$percorsoFilm/locandina.jpg' >";
    echo "<a href='../guardafilm.php' class='btn'>Guarda Subito!</a>";
    echo "<a onclick='lista()' id='lista' class='btn'>Aggiungi alla lista!</a></div>";
    $myfile = fopen($percorsoFilm."/trama.txt", "r") or die("Unable to open file!");
        $trama = "";
        for($i = 0;$i < 600;$i++) {
            $trama = $trama . fgetc($myfile);
        }
        fclose($myfile);
        $trama = $trama . "...";
    echo "<hr><div><h1>Trama</h1><br><p>" . $trama . "</p></div>";
    ?>
</div>