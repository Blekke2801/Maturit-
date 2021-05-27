<?php
if (isset($_GET["NomeFilm"])) {
    $nome = $_GET["NomeFilm"];
    if (isset($_GET["site"]) && $_GET["site"] == "fisico") {
        $Dati = take_film_prenota($nome);
    } else {
        Header("Location:./Home.php");
    }
    if (sizeof($Dati) < 1) {
        Header("Location:Home.php");
    } else {
        $percorsoFilm = "../../films/prenota/" . $nome;
    }
} else {
    Header("Location:./Home.php");
}
//titolo,riga,prenota/guarda se loggato,locandina,riga,genere,trama
?>
<div>
    <?php
    $disabled = false;

    echo "<h1>$Nome</h1><hr>";
    echo "<img src='$percorso/locandina.jpg>";
    echo "<a href='../guardafilm.php' class='btn'>Guarda Subito!</a>";
    echo "<a href='../Home.php?page=Lista' class='btn'>Aggiungi alla lista!</a>";
    ?>
</div>