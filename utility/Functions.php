<?php
//pogina contenente tutte le funzioni del sito
DEFINE("User", "root"); //utente che ha un numero limitato di permessi nome="utente_sicuro" pwd="JteENZgJhMn7"
define("pwd", "");
//ci previene da un attacco a forza bruta
function checkbrute($user_id, $ip)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    // Recupero il timestamp
    $now = time();
    // Vengono analizzati tutti i tentativi_login di login a partire dalle ultime due ore.
    $valid_attempts = $now - (2 * 60 * 60);
    $sql = "SELECT time FROM tentativi_login WHERE User_ID = $user_id AND time > '$valid_attempts' AND ip = $ip";
    if ($result = $mysqli->query($sql)) {
        // Verifico l'esistenza di più di 5 tentativi_login di login falliti.
        if ($result->num_rows > 5) {
            $result->close();
            $mysqli->close();
            return true;
        } else {
            $result->close();
            $mysqli->close();
            return false;
        }
    }
}
//prende l'inidirzzo ip dell'utente
function getIPAddress()
{
    //se l'ip proviene dalla condivisione Internet
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //se l'ip arriva dal proxy  
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    //se l'ip arriva dal campo remote address  
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
//avvia una sessione sicura
function sec_session_start()
{
    $session_name = 'sec_session_id'; // Imposta un nome di sessione
    $secure = false; // Imposta il parametro a true se vuoi usare il protocollo 'https'.
    $httponly = true; // Questo impedirà ad un javascript di essere in grado di accedere all'id di sessione.
    ini_set('session.use_only_cookies', 1); // Forza la sessione ad utilizzare solo i cookie.
    $cookieParams = session_get_cookie_params(); // Legge i parametri correnti relativi ai cookie.
    session_set_cookie_params(
        $cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly
    );

    session_name($session_name); // Imposta il nome di sessione con quello prescelto all'inizio della funzione.
    session_start(); // Avvia la sessione php.
    session_regenerate_id(); // Rigenera la sessione e cancella quella creata in precedenza.
}

//effettua la registrazione
function register($nome, $cognome, $email, $pwd, $birth, $tariffa)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $sql = "SELECT COUNT(*) FROM utente WHERE Mail = '$email'"; //controlla se l'utente esiste già
    $exists = $mysqli->query($sql);
    if ($exists->fetch_row()[0] > 0) {
        $mysqli->close();
        return false;
    }
    $reg = $mysqli->prepare("INSERT INTO `utente` (`Mail`, `Password`, `Nome`, `Cognome`, `Data_Birth`, `Cln_Imp`, `Free_Premium`) VALUES ('$email', ?, '$nome', '$cognome', '$birth', true, ?)");
    $reg->bind_param("si", $pwd, $tariffa);
    if ($tariffa == "false") {
        $tariffa = false;
    } else {
        $tariffa = true;
    }
    $pwd = md5($pwd, FALSE); //pwd crypto md5
    $reg->execute() or die($mysqli->error);
    $reg->close();
    $mysqli->close();
    return true;
}
//effettua il login
function login($email, $password, $cookie)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $sql = "SELECT * FROM utente WHERE Mail = '$email' LIMIT 1";
    if ($result = $mysqli->query($sql)) {
        $row = $result->fetch_assoc();

        // recupera il risultato della query e lo memorizza nelle relative variabili.
        $user_id = $row["ID_User"];
        $username = $row["Mail"];
        $db_password = $row["Password"];
        $ruolo = $row["Cln_Imp"];
        if ($ruolo) {
            $tariffa = $row["Free_Premium"]; //0 per free 1 per premium
        }
        if (!$cookie) {
            $password = md5($password, FALSE); // codifica la password usando una chiave univoca.
        }
        if ($result->num_rows == 1) { // se l'utente esiste
            // verifichiamo che non sia disabilitato in seguito all'esecuzione di troppi tentativi_login di accesso errati.
            $result->close();
            if (checkbrute($user_id, getIPAddress())) {
                $mysqli->close();
                return false;
            } else {
                if ($db_password == $password) { // Verifica che la password memorizzata nel database corrisponda alla password fornita dall'utente.
                    // Password corretta!
                    $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.

                    $user_id = preg_replace("/[^0-9]+/", "", $user_id); // ci proteggiamo da un attacco XSS
                    $_SESSION['user_id'] = $user_id;
                    if (!empty($_POST["remember"])) {
                        $expires = time() + 7 * 24 * 60 * 60;
                        setcookie("user", serialize(array($username, $password)), $expires, "/"); //metto un cookie con valore un simil array che contiene username e password criptata
                    } else {
                        setcookie("user","",time()-(3600*24*7),"/");
                    }
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "_", $username); // ci proteggiamo da un attacco XSS
                    $_SESSION['username'] = $username;
                    $_SESSION["ruolo"] = $ruolo; // 1 per cliente, 0 per impiegato
                    if ($_SESSION["ruolo"]) {
                        $_SESSION["tariffa"] = $tariffa; //0 se free altrimenti premium
                    }
                    $_SESSION['login_string'] = md5($password . $user_browser, false);
                    $mysqli->close();
                    // Login eseguito con successo.
                    return true;
                } else {
                    // Password incorretta.
                    // Registriamo il tentativo fallito nel database.
                    $now = time();
                    $ip = getIPAddress();

                    $mysqli->query("INSERT INTO tentativi_login (user_id, time,ip) VALUES ('$user_id', '$now','$ip')");
                    $mysqli->close();
                    return false;
                }
            }
        } else {
            $mysqli->close();
            // L'utente inserito non esiste.
            return false;
        }
    }
}
//effettua la funzione di logout
function logout()
{
    require_once 'functions.php';
    sec_session_start();
    // Elimina tutti i valori della sessione.
    $_SESSION = array();
    // Recupera i parametri di sessione.
    $params = session_get_cookie_params();
    // Cancella i cookie attuali.
    if (isset($_COOKIE['user'])) {
        setcookie("user","",time()-(3600*24*7),"/");
    }
    setcookie(
        session_name(),
        '',
        time() - 42000,
        $params["path"],
        $params["domain"],
        $params["secure"],
        $params["httponly"]
    );
    // Cancella la sessione.
    session_destroy();
    header('Refresh:0; url=Login.php');
}
//funzione che controlla se l'utente ha eseguito il login
function login_check()
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    // Verifica che tutte le variabili di sessione siano impostate correttamente
    if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
        $sql = "SELECT Password FROM utente WHERE ID_User = $user_id LIMIT 1";
        if ($result = $mysqli->query($sql)) {
            if ($result->num_rows == 1) { // se l'utente esiste
                $row = $result->fetch_assoc();
                $result->close();
                $password = $row["Password"]; // recupera le variabili dal risultato ottenuto.
                $login_check = md5($password . $user_browser, FALSE);

                if ($login_check == $login_string) {
                    $mysqli->close();
                    // Login eseguito!!!!
                    return true;
                } else {
                    $mysqli->close();
                    // Login non eseguito
                    return false;
                }
            } else {
                $mysqli->close();
                // Login non eseguito
                return false;
            }
        } else {
            $mysqli->close();
            // Login non eseguito
            return false;
        }
    } else {
        $mysqli->close();
        // Login non eseguito
        return false;
    }
}
//se la variabile nome non è settata prende tutti i titoli film, altrimenti prende i dati del film indicato in $nome
function take_film_stream($nome = NULL)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $Dati = array();
    if ($nome == NULL) {
        $result = $mysqli->query("SELECT * FROM film_stream") or die($mysqli->error);
        $films = array();
        $i = 0;
        while ($row = $result->fetch_row()) {
            $films[$i] = $row;
            $i++;
        }
        $mysqli->close();
        return $films;
    } else {
        $nome = strtolower($nome);
        $resultStream = $mysqli->query('SELECT * FROM film_stream where Titolo = "'.$nome.'" LIMIT 1') or die($mysqli->error);
        $Dati = $resultStream->fetch_row() or die($resultStream->error);
        if (sizeof($Dati) > 0) {
            $mysqli->close();
            return $Dati;
        } else {
            $mysqli->close();
            return array();
        }
    }
}
//come la funzione take_film_stream ma utilizza l'id
function take_film_prenota($id = NULL)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $Dati = array();
    if (isset($id)) {
        $resultStream = $mysqli->query("SELECT * FROM film_prenotabili where ID_Film = '$id' LIMIT 1") or die($mysqli->error);
        $Dati = $resultStream->fetch_row() or die($resultStream->error);
        if (sizeof($Dati) > 0) {

            $mysqli->close();
            return $Dati;
        } else {
            $mysqli->close();
            return array();
        }
    } else {
        $resultStream = $mysqli->query("SELECT * FROM film_prenotabili;") or die($mysqli->error);
        $i = 0;
        foreach ($resultStream as $single) {
            $Dati[$i] = $single;
            $i++;
        }
        if (sizeof($Dati) > 0) {

            $mysqli->close();
            return $Dati;
        } else {
            $mysqli->close();
            return array();
        }
    }
}
//restituisce i film che risultano nella ricerca effettuata dall'utente
function research($ricerca)
{
    $ricerca = strtolower($ricerca);
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $result = $mysqli->query("SELECT * FROM film_stream Where Titolo like '$ricerca%'");

    $films = array();
    $i = 0;
    while ($row = $result->fetch_row()) {
        $films[$i] = $row;
        $i++;
    }
    $mysqli->close();
    return $films;
}
//restituisce i dati dei film nella lista dell'utente
function lista()
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die($mysqli->error);
    $records = $mysqli->query("SELECT film_stream.*,lista.* FROM lista,film_stream where lista.ID_Film = film_stream.ID_Film AND lista.ID_User = " . $_SESSION['user_id']) or die($mysqli->error);
    $films = array();
    $i = 0;
    while ($row = $records->fetch_row()) {
        $films[$i] = $row;
        $i++;
    }
    $mysqli->close();
    return $films;
}

//aggiunge alla lista il film indicato
function addLista($Titolo)
{
    $dati = take_film_stream($Titolo);
    $user = null;
    $film = null;
    if (sizeof($dati) > 0) {
        $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
        $search = $mysqli->prepare("SELECT * from lista where ID_User = ? AND ID_Film = ?;") or die($mysqli->error);
        $search->bind_param("ii", $user, $film);
        $user = $_SESSION['user_id'];
        $film = $dati[0];
        $search->execute() or die("Error description: " . $search->error);
        if (!$search->fetch()) {
            $search->close();
            $stmt = $mysqli->prepare("INSERT INTO lista values (?,?);") or die($mysqli->error);
            $stmt->bind_param("ii", $user, $film);
            $stmt->execute() or die("Error description: " . $stmt->error);
            $stmt->close();
            $mysqli->close();
            header("Location: Home.php?NomeFilm=" . $Titolo);
        }
        $search->close();
        $mysqli->close();
        header("Location: Home.php?NomeFilm=" . $Titolo);
    } else
        header("Location: Home.php");
}
//rimuove alla lista il film indicato
function removeLista($Titolo)
{
    $dati = take_film_stream($Titolo);
    $user = null;
    $film = null;
    if (sizeof($dati) > 0) {
        $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
        $search = $mysqli->prepare("SELECT * from lista where ID_User = ? AND ID_Film = ?;") or die($mysqli->error);
        $search->bind_param("ii", $user, $film);
        $user = $_SESSION['user_id'];
        $film = $dati[0];
        $search->execute() or die("Error description: " . $search->error);
        if ($search->fetch()) {
            $search->close();
            $stmt = $mysqli->prepare("DELETE from lista where ID_User = ? AND ID_Film = ?;") or die($mysqli->error);
            $stmt->bind_param("ii", $user, $film);
            $stmt->execute() or die("Error description: " . $stmt->error);
            $stmt->close();
            $mysqli->close();
            header("Location: Home.php?NomeFilm=" . $Titolo);
        }
        $search->close();
        $mysqli->close();
        header("Location: Home.php?NomeFilm=" . $Titolo);
    } else
        header("Location: Home.php");
}
//ottiene tutti gli orari di un film, se preciso è settato l'orario con id = preciso
function prendi_orari($id, $preciso = null)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    if ($preciso == NULL) {
        $Dati = array();
        $resultStream = $mysqli->query("SELECT * FROM timetable where ID_Film = $id") or die($mysqli->error);
        $i = 0;
        $today = date("Y-m-d");
        foreach ($resultStream as $row) {
            if ($row["Data"] > $today) {
                $Dati[$i] = $row;
                $i++;
            }
        }
        if (sizeof($Dati) > 0) {
            $mysqli->close();
            return $Dati;
        } else {
            $mysqli->close();
            return array();
        }
    } else {
        $Dati = array();
        $resultStream = $mysqli->query("SELECT * FROM timetable where ID_TimeTable = $preciso LIMIT 1;") or die($mysqli->error);
        $i = 0;
        $Dati = $resultStream->fetch_row();
        if (sizeof($Dati) > 0) {
            $mysqli->close();
            return $Dati;
        } else {
            $mysqli->close();
            return array();
        }
    }
}

//controlla tutti i posti già prenotati in un determinato orario un determinato giorno
function Prenotato($ID_TIME)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $Biglietti = array();
    $resultStream = $mysqli->query("SELECT posto FROM biglietto where ID_TimeTable = $ID_TIME;") or die($mysqli->error);
    $i = 0;
    foreach ($resultStream as $row) {
        $Biglietti[$i] = $row["posto"];
        $i++;
    }
    return $Biglietti;
}

//controlla tutti i biglietti o il singolo biglietto a seconda di id (se è null li prende tutti)
function biglietti($id = null)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    if ($id == NULL) {
        $resultStream = $mysqli->query("SELECT biglietto.*, timetable.Data, timetable.ora,timetable.sala, film_prenotabili.Titolo,film_prenotabili.genere FROM biglietto,timetable,film_prenotabili where biglietto.ID_TimeTable = timetable.ID_TimeTable AND timetable.ID_Film = film_prenotabili.ID_Film AND ID_User = " . $_SESSION['user_id'] . ";") or die($mysqli->error);
        $Biglietti = array();
        $i = 0;
        foreach ($resultStream as $row) {
            $Biglietti[$i] = $row;
            $i++;
        }
        $mysqli->close();
        return $Biglietti;
    } else {
        $resultStream = $mysqli->query("SELECT biglietto.*, timetable.Data, timetable.ora,timetable.sala, film_prenotabili.Titolo,film_prenotabili.genere FROM biglietto,timetable,film_prenotabili where biglietto.ID_TimeTable = timetable.ID_TimeTable AND timetable.ID_Film = film_prenotabili.ID_Film AND ID_User = " . $_SESSION['user_id'] . " AND ID_Ticket = $id;") or die($mysqli->error);
        $Biglietto = $resultStream->fetch_row();
        $mysqli->close();
        return $Biglietto;
    }
}

//prende tutti i film disponibili la settimana corrente
function this_week()
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $result = $mysqli->query("SELECT * FROM TimeTable where week(data) = week(CURDATE()) OR week(data) = week(CURDATE()) + 1;");
    $films = array();
    $i = 0;
    foreach ($result as $row) {
        $films[$i] = $row;
        $i++;
    }
    $mysqli->close();
    return $films;
}

//prenota il biglietto
function prenota($id, $posto)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $result = $mysqli->query("SELECT COUNT(ID_Ticket) FROM biglietto where posto = '$posto' AND ID_TimeTable = $id;") or die($mysqli->error);
    $rows = $result->fetch_row() or false;
    if ($rows[0] == "0" || $rows == false) {
        $reg = $mysqli->prepare("INSERT INTO biglietto (ID_User,posto,ID_TimeTable) VALUES (?,'$posto',$id);") or die($mysqli->error);
        $user = NULL;
        $reg->bind_param("i", $user);
        $user = $_SESSION['user_id'];
        $reg->execute() or die($mysqli->error);
        $reg->close();
        $mysqli->query("UPDATE timetable SET liberi = liberi-1 WHERE ID_TimeTable = $id;") or die($mysqli->error);
        $reg = $mysqli->query("SELECT Max(ID_Ticket) as 'ticket' FROM biglietto;") or die($mysqli->error);
        $idB = $reg->fetch_row();
        $mysqli->close();
        return $idB[0];
    } else {
        $mysqli->close();
        return false;
    }
}

//operazione dell'admin che permette di inserire un nuovo film e di aggiungerlo nella cartella del server
function new_film()
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $titolo = strtolower($_POST["titolo"]);
    $genere = strtolower($_POST["genere"]);
    $durata = $_POST["durata"];
    $tariffa = $_POST["tariffa"];
    $locandinaV = $_FILES["locandinaV"];
    $locandinaH = $_FILES["locandinaH"];
    $film = $_FILES["film"];
    $trama = $_FILES["trama"];
    $result = $mysqli->query('SELECT COUNT(ID_Film) from film_stream where Titolo = "'.$titolo.'"') or die($mysqli->error);
    $controllo = $result->fetch_row();
    if ($controllo[0] == "0") {
        $result = $mysqli->prepare('INSERT INTO film_stream (Titolo,Data_Add,Genere,Free_Premium,durata) VALUES ("'.$titolo.'",?,"'.$genere.'",?,'.$durata.');');
        $cartella = "../films/stream/$titolo";
        mkdir($cartella, 0700);
        $result->bind_param("si", $data, $tariffa);
        $data = date("Y-m-d");
        if ($tariffa == "free") {
            $tariffa = false;
        } else {
            $tariffa = true;
        }
        $result->execute();
        //rename($locandinaV["name"], "locandina.jpg");
        move_uploaded_file($locandinaV["tmp_name"], $cartella . "/locandina.jpg");
        move_uploaded_file($locandinaH["tmp_name"], $cartella . "/horizontal.jpg");
        //rename($locandinaH["name"], "horizontal.jpg");
        move_uploaded_file($trama["tmp_name"], $cartella . "/trama.txt");
        //rename($trama["name"], "trama.txt");
        mkdir($cartella . "/film", 0700);
        $estensione = explode(".", $film["name"]);
        move_uploaded_file($film["tmp_name"], $cartella . "/film/it." . $estensione[1]);
        $result->close();
        $mysqli->close();
        return true;
    } else {
        $mysqli->close();
        return false;
    }
}
function searchFile($name)
{
    // reads informations over the path
    $info = pathinfo($name);
    if (!empty($info['extension'])) {
        // if the file already contains an extension returns it
        return $name;
    }
    $filename = $info['filename'];
    $len = strlen($filename);
    // open the folder
    $dh = opendir($info['dirname']);
    if (!$dh) {
        return false;
    }
    // scan each file in the folder
    while (($file = readdir($dh)) !== false) {
        if (strncmp($file, $filename, $len) === 0) {
            if (strlen($name) > $len) {
                // if name contains a directory part
                $name = substr($name, 0, strlen($name) - $len) . $file;
            } else {
                // if the name is at the path root
                $name = $file;
            }
            closedir($dh);
            return $name;
        }
    }
    // file not found
    closedir($dh);
    return false;
}
//operazione dell'admin che permette di aggiungere una nuova tabella oraria
function new_hour()
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $data = $_POST["data"];
    $ora = $_POST["ora"];
    $sala = $_POST["sala"];
    $film = $_POST["id_film"];
    $result = $mysqli->query("SELECT COUNT(ID_TimeTable) from timetable where Data = '$data' AND ora = '$ora' AND sala = $sala AND ID_Film = $film;");
    $controllo = $result->fetch_row();
    if ($controllo[0] == "0") {
        if ($mysqli->query("INSERT INTO TimeTable (Data,ora,sala,ID_Film) VALUES ('$data','$ora',$sala,$film);")) {
            $mysqli->close();
            return true;
        } else {
            $mysqli->close();
            return false;
        }
    } else {
        $mysqli->close();
        return false;
    }
}
?>