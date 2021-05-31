<?php
if (isset($_GET["NomeFilm"])) {
    $nome = $_GET["NomeFilm"];
    if (!isset($_GET["site"]) || $_GET["site"] != "fisico") {
        $Dati = take_film_stream($nome);
    } else {
        Header("Location:./Home.php");
    }
    if (sizeof($Dati) == 0) {
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
    $DBName = $nome;
    $tariffa = $Dati[4];
    if ($tariffa && !$_SESSION["Free_Premium"]) {
        $disabled = "disabled";
    } else {
        $disabled = "";
    }
    $nome[0] = strtoupper($nome[0]);
    echo "<h1>$nome</h1><hr>";
    echo "<div class='filmRow1'><img src='$percorsoFilm/locandina.jpg' >";
    echo "<a href='guardafilm.php' class='btn'>Guarda Subito!</a>";
    echo "<a onclick='lista(this.value)' id='lista' class='btn' value='" . $DBName . "' >Aggiungi alla lista!</a></div>";
    $myfile = fopen($percorsoFilm . "/trama.txt", "r") or die("Unable to open file!");
    $trama = "";
    for ($i = 0; $i < 600; $i++) {
        $trama = $trama . fgetc($myfile);
    }
    fclose($myfile);
    $trama = $trama . "...";
    $genere = $Dati[2];
    $durata = $Dati[3];
    echo "<hr><div><h4>Genere:</h4><p>$genere</p><br><h4>Durata: </h4><p>$durata</p></div>";
    echo "<hr><div><h1>Trama</h1><br><p>" . $trama . "</p></div>";
    ?>
</div>