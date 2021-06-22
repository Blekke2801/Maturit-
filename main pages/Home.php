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
} else if (isset($_GET["logout"]) && $_GET["logout"] === "true") {
    logout(); //cliccando su logout viene ricaricata la pagina con $_GET["logout" = true, così da poter effettuare il logout
} else if (isset($_COOKIE["user"])) {
    $data = unserialize($_COOKIE["user"]); //il cookie ha un value creato dalla funzione serialize che restituisce un simil array formato da 2 campi inseriti, in questo caso sono stati inseriti user e pwd criptata
    Login($data[0], $data[1], true);
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
    if (!login_check()) {
?>
    <div class="main">
        <h1 style="color:#fff">Benvenuto nel nostro sito!</h1>
        <p style="color:#fff">Esegui il login o registrati per utilizzare il nostro sito al massimo!</p>
        <div class="regLog">
            <a class="btn" href="Login.php">LOGIN</a>
            <a class="btn" href="register.php">REGISTRATI</a>
        </div>
        <div class="carousel">
            <main id="carousel">
                <?php
                $allFilms = take_film_stream();
                $take = array();
                for ($i = 0; $i < 5; $i++) {
                    if (sizeof($take) > 0) {
                        echo "ses";
                        while (array_search($randIndex, $take, true) !== false) {
                            $randIndex = rand(0, sizeof($allFilms) - 1);
                        }
                    } else
                        $randIndex = rand(0, sizeof($allFilms) - 1);
                    $percorso = "../films/stream/" . $allFilms[$randIndex][1] . "/horizontal.jpg";
                    echo '<div name="item" id="' . $i . '" class="item"><img src="' . $percorso . '" class="horizontal"></label></div>';
                    $take[$i] = $randIndex;
                }
                ?>
            </main>

        </div>

    </div>
    </div>
    </div>
    </div>
    <?php } else if (isset($_GET["site"]) && $_GET["site"] == "fisico") {
    if (isset($_GET["ID_Film"])) { //film singolo
        $id = $_GET["ID_Film"];
        $Dati = take_film_prenota($id);
        $percorsoFilm = "../films/prenota/" . $Dati[1];
    ?>
        <div class="filmpage">
            <?php
            //dati del film
            $disabled = false;
            $nome = $Dati[1];
            $DBName = $nome;
            $nome[0] = strtoupper($nome[0]);
            echo "<h1>$nome</h1><hr>";
            //la pagina mostra titolo,locandina,parte della trama e altri dati del film
            echo "<div class='filmRow1'><img src='$percorsoFilm/locandina.jpg' >";
            //form per far comprare all'utente i biglietti per il film
            ?>
            <form action="Biglietto.php" method="POST">
                <input type="checkbox" name="ID_Film" value="<?php echo htmlentities($id); ?>" hidden checked>

                <select name="TimeTable">
                    <?php
                    $orari = prendi_orari($id);
                    foreach ($orari as $ora) {
                        echo '<option value="' . $ora["ID_TimeTable"] . '">Data:' . $ora["Data"] . " Alle ore:" . $ora["ora"] . '</option>';
                    }
                    ?>
                </select>
                <br>
                <input type="number" name="numero" min="1" max="40" step="1" value="1" placeholder="n°" required>
                <button type="submit">Prenota ora!</button>
            </form>
        </div>
<?php
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
        echo "<hr><div><h1>Trama</h1><br><p>" . $trama . "</p></div></div>";
    } else if (isset($_GET["page"]) && $_GET["page"] == "biglietti") { //elenco dei film al cinema
        echo "<h3> Ecco i Film disponibili nel nostro sito</h3><hr>";
        $t = biglietti();
        if (sizeof($t) > 0) {
            foreach ($t as $idB) {
                $titolo = $idB["Titolo"];
                $genere = $idB["genere"];
                $data = $idB["Data"];
                $ora = $idB["ora"];
                $posto = $idB["posto"];
                $sala = $idB["sala"];
                $percorsoFilm = "../films/prenota/" . $titolo;
                $img = $percorsoFilm . "/horizontal.jpg";
                echo "<a href='ticketBooked.php?id=" . $idB["ID_Ticket"] . "' class='ticket'><img class='locandinaElenco' src='$img'><div class='infos'><p>Data: $data</p><p>Ora: $ora</p><p>Sala: $sala</p><p>Posto: $posto</p></div></a>";
            }
        } else {
            echo "<h4>Nessun risultato trovato</h4>";
        }
    } else {
        echo "<h1 style='color:white'> film in questa settimana </h1>";
        $Table = this_week();
        foreach ($Table as $row) {
            $single = take_film_prenota($row["ID_Film"])[1];
            $percorsoFilm = "../films/prenota/" . $single;
            $Titolo = $single;
            $cartella = $Titolo;
            $Titolo[0] = strtoupper($Titolo[0]);
            $img = $percorsoFilm . "/horizontal.jpg";
            $Percorsotrama = $percorsoFilm . "/trama.txt";
            $myfile = fopen($Percorsotrama, "r") or die("Unable to open file!");
            $trama = "";
            for ($i = 0; $i < 200; $i++) {
                $trama = $trama . fgetc($myfile);
            }
            fclose($myfile);
            $trama = $trama . "...";
            echo "<a href='Home.php?site=fisico&ID_Film=" . $row["ID_Film"] . "' class='film'><img class='locandinaElenco' src='$img'><p class='tramaIntro'>$trama</p><h1 class='text'>$Titolo</h1></a>";
        }
    }
    //elenco dei film al cinema
} else {
    
    if (!isset($_GET["NomeFilm"])) {
        if (isset($_GET["ricerca"])) {
            $films = research($_GET["ricerca"]);
        } else if(isset($_GET["page"]) && $_GET["page"] == "lista"){
            $films = lista();
        }else {
            $films = take_film_stream();
        }
        echo "<div>";
        if (isset($_GET["ricerca"])) {
            echo "<h3> Ecco i risultati per la ricerca: " . $_GET["ricerca"] . "</h3><hr>";
        } else {
            echo "<h3> Ecco i Film disponibili nel nostro sito</h3><hr>";
        }
        if (sizeof($films) > 0) {
            foreach ($films as $single) {
                $percorsoFilm = "../films/stream/" . $single[1];
                $Titolo = $single[1];
                $cartella = $Titolo;
                $Titolo[0] = strtoupper($Titolo[0]);
                $img = $percorsoFilm . "/horizontal.jpg";
                $Percorsotrama = $percorsoFilm . "/trama.txt";
                $myfile = fopen($Percorsotrama, "r") or die("Unable to open file!");
                $trama = "";
                for ($i = 0; $i < 200; $i++) {
                    $trama = $trama . fgetc($myfile);
                }
                fclose($myfile);
                $trama = $trama . "...";
                if ($single[4]) {
                    $crown = "<img class='crown' src='../img/crown.png'>";
                } else {
                    $crown = "";
                }
                echo "<a href='Home.php?NomeFilm=$cartella' class='film'><img class='locandinaElenco' src='$img'><p class='tramaIntro'>$trama</p>$crown<h1 class='text'>$Titolo</h1></a>";
            }
        } else {
            echo "<h4>Nessun risultato trovato</h4>";
        }
        echo "</div>";
    } else {
        echo '<div class="filmpage">';
        $nome = $_GET["NomeFilm"];
        $Dati = take_film_stream($nome);
        $percorsoFilm = "../films/stream/" . $nome;
        $disabled = false;
        $DBName = $nome;
        $tariffa = $Dati[4];
        if (isset($_SESSION["tariffa"])) {
            if ($tariffa != $_SESSION["tariffa"] || $_SESSION["tariffa"]) {
                $disabled = "disabled";
            } else {
                $disabled = "";
            }
        } else {
            $disabled = "disabled";
        }
        $nome[0] = strtoupper($nome[0]);
        echo "<h1>$nome</h1><hr>";
        echo "<div class='filmRow1'><img src='$percorsoFilm/locandina.jpg' >";
        echo "<button onclick='location.href=\"guardafilm.php?film=" . $Dati[1] . "\"' class='btn'>Guarda Subito!</button>";
        $lista = lista();
        $trovato = false;
        foreach ($lista as $film) {
            if ($Dati[0] == $film[0]) {
                $trovato = true;
                break;
            }
        }
        if ($trovato) {
            $gest = "Rimuovi dalla lista!";
        } else {
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
    }
} ?>
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