<!DOCTYPE html>
<html>
<?php
//pagina home
require("../utility/Functions.php"); // file con tutte le funzioni
sec_session_start(); //avvia la sessione sicura
if (isset($_POST["user"])) { //se è settato vuol dire che arriva da Login quindi lo effettuo
    $user = htmlentities($_POST["user"]);
    $pwd = htmlentities($_POST["pwd"]);
    login($user, $pwd, false);
} else if (isset($_COOKIE["user"])) {
    $data = unserialize($_COOKIE["user"]); //il cookie ha un value creato dalla funzione serialize che restituisce un simil array formato da 2 campi inseriti, in questo caso sono stati inseriti user e pwd criptata
    Login($data[0], $data[1], true);
} else if (isset($_GET["logout"]) && $_GET["logout"] === "true") {
    Logout(); //cliccando su logout viene ricaricata la pagina con $_GET["logout" = true, così da poter effettuare il logout
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
    <!-- navbar -->
    <nav>
        <img src="../img/logo.jfif" alt="Brand">
        <div class="searchbar">
            <input id="search" type="text" placeholder="Cerca un film">
            <label class="btn" for="search" id="btnsrc" onclick="ricerca()">Go</label>
        </div>
        <div class="account">
            <?php if (!login_check()) { //se l'utente ha effettuato il login viene mostrato nome utente e pulsante per prenotare i biglietti, altrimenti 2 pulsanti per login o registrazione
            ?>
                <a class="btn" href="Login.php">Login</a>
                <a class="btn" href="register.php">Register</a>
            <?php } else {
                if ($_SESSION["ruolo"]) {
                    echo '<a class="btn" href="Home.php?site=fisico">Prenota i Biglietti</a>';
                } ?>


                <nav role="navigation">
                    <ul>
                        <li class="btn main-drop"><a><?php echo $_SESSION["username"]; ?></a>
                            <ul class="dropdown">
                                <?php
                                if (!$_SESSION["ruolo"]) {
                                    echo '<li><a href="AdminPage.php">Operazioni admin</a></li>';
                                } else {
                                    echo '<li><a href="Home.php?site=fisico&page=biglietti">I miei biglietti</a></li>';
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
    //a seconda del campo $_GET["site"] il sito si divide in 2, la parte di prenotazione e la parte di streaming
    if (!isset($_GET["site"]) || $_GET["site"] != "fisico") {
        if (isset($_GET["page"])) // se non è specificata la parte del sito verrà mostrata di default la zon streming con pagina specifica e non, il tutto viene mostrato con include
            $page = $_GET["page"];
        else
            $page = "null";
        switch ($page) {
            case "lista":
                if (login_check()) {
                    include("includes/lista.php"); //elenco dei film nella lista dell'utente
                } else {
                    include("includes/base.php"); //pagina di benvenuto
                }
                break;
            case "cerca":
                include("includes/elenchi_film.php"); //elenco dei film in streaming che risultano nella ricerca effettuata
                break;
            case "novita":
                include("includes/elenchi_film.php"); // film appena aggiunti(non sviluppato per il momento)
                break;
            default:

                if (isset($_GET["NomeFilm"])) {
                    include("includes/film_page_stream.php"); //elenco dei film in streming
                } else if (login_check()) {
                    include("includes/elenchi_film.php"); //elenco dei film nella lista dell'utente
                } else {
                    include("includes/base.php"); //pagina di benvenuto
                }
                break;
        }
    } else if ($_GET["site"] == "fisico") {
        if (isset($_GET["ID_Film"]))
            include("includes/film_page_fisico.php"); //film singolo
        else if (isset($_GET["page"]) && $_GET["page"] == "biglietti")
            include("includes/elencoBiglietti.php"); //elenco dei film al cinema
        else
            include("includes/prenota.php"); //elenco dei film al cinema
    }


    ?>
    <div class="contacts">
        <h1>Contattaci:</h1>
        <div>
            <div>
                <span>Email:email.cinema@cinema.com;</span>
                <span>Numero telefonico: 1234567890;</span>
            </div>
            <div>
                <span>Indirizzo: via cinema 3, (LE);</span>
            </div>
        </div>
    </div><!-- zona in cui sono indicati i possibili contatti con il cinema (non compilato)-->
</body>

</html>