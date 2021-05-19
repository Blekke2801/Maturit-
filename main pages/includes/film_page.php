<?php
if (isset($_GET["NomeFilm"])) {
    if (!searchFilm($mysqli,$_GET["NomeFilm"])){
        Header("Location:Home.php");
    }
    if(searchFilm($mysqli,$_GET["NomeFilm"])){
        $percorsoFilm = "../../films/".$_GET["NomeFilm"];
    }else {
        Header("Location:Home.php");
    }
} else {
    Header("Location:Home.php");
}
//titolo,riga,prenota/guarda se loggato,locandina,riga,genere,trama
?>
<div>

</div>