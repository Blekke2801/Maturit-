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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComVid</title>
    <script src="../js/featuresHome.js"></script>
    <link href="../utility/css/style_home.css" rel="stylesheet">
</head>

<body>
    <?php
    if (isset($_COOKIE["user"])) {
        LoginCookie($mysqli);
    }
    ?>
    <nav>
        <img src="../img/logo.jfif" alt="Brand">
        <div class="searchbar">
            <input id="search" type="text" placeholder="Cerca un film">
            <label class="btn" for="search" id="btnsrc" onclick="ricerca()">Go</label>
        </div>
        <div style="display: flex;">
            <a class="btn" href="Login.php">Login</a>
            <a class="btn" href="register.php">Register</a>
        </div>
    </nav>
    <div class="subnav">
        <a class="btn" href="Home.php">Home</a>
        <a class="btn" href="?page=lista">Lista</a>
        <a class="btn" href="">Generi</a>
        <a class="btn" href="?page=novita">Novità</a>
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
            case "lista":
                if (login_check($mysqli)) {
                    include("includes/elenchi_film.php"); //elenco dei film al cinema
                } else {
                    include("includes/base.php");
                }
                break;
            case "cerca":
                include("includes/elenchi_film.php");
                break;
            case "novita":
                include("includes/elenchi_film.php");
                break;
            default:
                Header("Location:Home.php");
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