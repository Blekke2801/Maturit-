<?php
if (isset($_GET["NomeFilm"])) {
    $nome = $_GET["NomeFilm"];
    $tipo = searchFilm($nome);
    if ($tipo == false){
        Header("Location:../Home.php");
    }else {
        take_film($tipo,$nome);
        $percorsoFilm = "../../films/".$nome;
    }
} else {
    Header("Location:../Home.php");
}
//titolo,riga,prenota/guarda se loggato,locandina,riga,genere,trama
?>
<div>
<?php 
$disabled = false;
if($Dati)
echo "<h1>$Nome</h1><hr>";
echo "<img src='$percorso/locandina.jpg>";
if(!$prenota)
    echo "<a href='../guardafilm.php' class='btn'>Guarda Subito!</a>";
else 
    echo "<a href='../guardafilm.php' class='btn'>Prenota Subito!</a>";
    ?>
</div>