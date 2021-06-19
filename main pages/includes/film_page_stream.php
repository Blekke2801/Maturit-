<?php
//uguale a film_page_fisico ma al posto del form per prenotare ci sono 2 pulsanti: 1 per vedere il film, l'altro per aggiungerlo alla lista(nessuno dei 2 funziona)
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
?>
<div class="filmpage">
    <?php

    $disabled = false;
    $DBName = $nome;
    $tariffa = $Dati[4];
    if (isset($_SESSION["tariffa"])) {
        if ($tariffa != $_SESSION["tariffa"] || $_SESSION["tariffa"]) {
            $disabled = "disabled";
        } else {
            $disabled = "";
        }
    }else {
        $disabled = "disabled";
    }
    $nome[0] = strtoupper($nome[0]);
    echo "<h1>$nome</h1><hr>";
    echo "<div class='filmRow1'><img src='$percorsoFilm/locandina.jpg' >";
    echo "<button onclick='location.href=\"guardafilm.php?film=".$Dati[1]."\"' class='btn'>Guarda Subito!</button>";
    $lista = lista();
    if(array_search($Dati[0],$lista,true) !== false){
        $gest = "Rimuovi dalla lista!";
    }else {
        $gest = "Aggiungi alla lista!";
    }
    echo "<button onclick='lista(this.value)' id='lista' class='btn' value='" . $DBName . "' >$gest</button></div>";
    $myfile = fopen($percorsoFilm . "/trama.txt", "r") or die("Unable to open file!");
    $trama = "";
    for ($i = 0; $i < 600; $i++) {
        $trama = $trama . fgetc($myfile);
    }
    fclose($myfile);
    $trama = $trama . "...";
    $genere = $Dati[3];
    $durata = $Dati[5];
    echo "<hr><div><h4>Genere:</h4><p>$genere</p><br><h4>Durata: </h4><p>$durata</p></div>";
    echo "<hr><div><h1>Trama</h1><br><p>" . $trama . "</p></div>";
    ?>
</div>