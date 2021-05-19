<!DOCTYPE html>
<html>
<?php
require("../utility/Functions.php");
require("../utility/connect.php");
sec_session_start();
if (isset($_POST["user"])) {
    $user = $_POST["user"];
    $pwd = $_POST["pwd"];
    login($user, $pwd, $mysqli);
}
?>

<head>
    <meta charset="utf-8" />
    <title>ComVid</title>
    <script src="../js/featuresHome.js"></script>
    <link href="../utility/css/style_home.css" rel="stylesheet">
</head>

<body onscroll="scrollMenu()">
    <?php
    if (isset($_COOKIE["user"])) {
        $user = $_COOKIE["user"];
        Login($user[0], $user[1], $mysqli);
    }
    ?>
    <div id="head">
        <div class="row1">
            <img src="../img/logo.jfif">
            <form action="elenchi_film.php" role="search">
                <label for="search">Search for stuff</label>
                <input id="search" type="search" placeholder="Search..." autofocus required />
                <button class="search" type="submit">Go</button>
            </form>
            <div class="topbtns">
                <a class="btn" href="?page=prenota.php">Prenota i Biglietti</a>
                <a class="btn" href="Login.php">Login/Account</a>
            </div>

        </div>
        <div class="row2">
            <a class="btn" href="#">HOME</a>
            <a class="btn" href="#">LISTA</a>
            <div class="dropdown" id="generi">
                <a class="btn">GENERI</a>
                <div id="elencoGen" class="dropdown-content">
                    <a href="#">genere 1</a>
                    <a href="#">genere 2</a>
                    <a href="#">genere 3</a>
                </div>
            </div>
            <a class="btn" href="#">NOVITÀ</a>
        </div>
    </div>

    <?php
    if (isset($_GET["page"])) {
        $page = $_GET["page"];
        switch ($page) {
            case "filmpage":
                include("includes/film_page.php"); //film singolo
                break;
            case "prenota":
                include("includes/prenota.php"); //elenco dei film al cinema
                break;
        }
    } else if (login_check($mysqli)) {
        include("includes/elenchi_film.php"); //film in streaming disponibili
    } else {
        include("includes/base.php"); //pagina benvenuto
    }
    ?>

</body>

</html>