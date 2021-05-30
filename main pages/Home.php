<!DOCTYPE html>
<html>
<?php
require("../utility/Functions.php");
sec_session_start();
if (isset($_POST["user"])) {
    $user = htmlentities($_POST["user"]);
    $pwd = htmlentities($_POST["pwd"]);
    login($user, $pwd, false);
} else if (isset($_COOKIE["user"])) {
    $data = unserialize($_COOKIE["user"]);
    Login($data[0], $data[1], true);
} else if (isset($_GET["logout"]) && $_GET["logout"] === "true") {
    Logout();
}

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ComVid</title>
    <script src="../js/features.js"></script>
    <link href="../utility/css/style_home.css" rel="stylesheet">
    <link rel="icon" href="../img/favicon.ico" />
</head>

<body>
    <nav>
        <img src="../img/logo.jfif" alt="Brand">
        <div class="searchbar">
            <input id="search" type="text" placeholder="Cerca un film">
            <label class="btn" for="search" id="btnsrc" onclick="ricerca()">Go</label>
        </div>
        <div class="account">
            <?php if (!login_check()) { ?>
                <a class="btn" href="Login.php">Login</a>
                <a class="btn" href="register.php">Register</a>
            <?php } else {
            ?>
                <a class="btn" href="Home.php?site=fisico">Prenota i Biglietti</a>
                <nav role="navigation">
                    <ul>
                        <li class="btn main-drop"><a><?php echo $_SESSION["username"]; ?></a>
                            <ul class="dropdown">
                                <?php
                                if (!$_SESSION["ruolo"]) {
                                    echo '<li><a href="NuovoFilm.php">Aggiungi film</a></li>';
                                } ?>
                                <li><a href="Home.php?logout=true">Logout</a></li>
                            </ul>
                        </li>
                    </ul>
                </nav> <?php
                    } ?>
        </div>
    </nav>
    <div class="subnav">
        <a class="btn" href="Home.php">Home</a>
        <a class="btn" href="?page=lista">Lista</a>
        <nav role="navigation">
            <ul>
                <li class="btn main-drop"><a>Generi</a>
                    <ul class="dropdown">
                        <li><a href="#">Sub-1</a></li>
                        <li><a href="#">Sub-2</a></li>
                        <li><a href="#">Sub-3</a></li>
                    </ul>
                </li>
            </ul>
        </nav>
        <a class="btn" href="?page=novita">Novità</a>
    </div>

    <?php
    if (!isset($_GET["site"]) || $_GET["site"] != "fisico") {
        if (isset($_GET["page"]))
            $page = $_GET["page"];
        else
            $page = "ciao";
        switch ($page) {
            case "lista":
                if (login_check()) {
                    include("includes/lista.php"); //elenco dei film nella lista dell'utente
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

                if (isset($_GET["NomeFilm"])) {
                    include("includes/film_page_stream.php"); //elenco dei film al cinema
                } else if (login_check()) {
                    include("includes/elenchi_film.php"); //elenco dei film nella lista dell'utente
                } else {
                    include("includes/base.php");
                }
                break;
        }
    } else if ($_GET["site"] == "fisico") {
        if (!isset($_GET["ID_Film"]))
            include("includes/prenota.php"); //elenco dei film al cinema
        else
            include("includes/film_page_fisico.php"); //film singolo
    }


    ?>
    <div class="contacts"></div>
</body>

</html>