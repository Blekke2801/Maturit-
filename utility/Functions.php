<?php
DEFINE("User", "root");
define("pwd", "");
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
function getIPAddress()
{
    //whether ip is from the share internet  
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    }
    //whether ip is from the proxy  
    else if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    //whether ip is from the remote address  
    else {
        $ip = $_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}
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
function register($nome, $cognome, $email, $pwd, $birth, $tariffa)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $sql = "SELECT COUNT(*) FROM utente WHERE Mail = '$email'";
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
function login($email, $password, $cookie)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    // Usando statement sql 'prepared' non sarà possibile attuare un attacco di tipo SQL injection.
    // ...ma noi non lo usiamo!
    $sql = "SELECT * FROM utente WHERE Mail = '$email' LIMIT 1";
    if ($result = $mysqli->query($sql)) {
        $row = $result->fetch_assoc();

        // recupera il risultato della query e lo memorizza nelle relative variabili.
        $user_id = $row["ID_User"];
        $username = $row["Mail"];
        $db_password = $row["Password"];
        $ruolo = $row["Cln_Imp"];
        if ($ruolo) {
            $_SESSION["tariffa"] = $row["Free_Premium"]; //0 per free 1 per premium
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
                        setcookie("user", serialize(array($username, $password)), time() + (86400 * 7));
                    } else {
                        setcookie("user", "");
                    }
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "_", $username); // ci proteggiamo da un attacco XSS
                    $_SESSION['username'] = $username;
                    $_SESSION["ruolo"] = $ruolo; // 1 per cliente, 0 per impiegato
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
function logout()
{
    require_once 'functions.php';
    sec_session_start();
    // Elimina tutti i valori della sessione.
    $_SESSION = array();
    // Recupera i parametri di sessione.
    $params = session_get_cookie_params();
    // Cancella i cookie attuali.
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
    header('Location: Login.php');
}
//Crea la funzione 'login_check':
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
function take_film_stream($nome = NULL)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $Dati = array();
    if ($nome == NULL) {
        $result = $mysqli->query("SELECT * FROM film_stream") or die($mysqli->error);
        $films = array();
        $i = 0;
        foreach ($result as $row) {
            $films[$i] = $row["Titolo"];
            $i++;
        }
        $mysqli->close();
        return $films;
    } else {
        $nome = strtolower($nome);
        $resultStream = $mysqli->query("SELECT * FROM film_stream where Titolo = '$nome' LIMIT 1") or die($mysqli->error);
        $Dati = $resultStream->fetch_assoc() or die($resultStream->error);
        if (sizeof($Dati) > 0) {
            $mysqli->close();
            return $Dati;
        } else {
            $mysqli->close();
            return array();
        }
    }
}
function take_film_prenota($id)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $Dati = array();
    $resultStream = $mysqli->query("SELECT * FROM film_prenotabili where ID_Film = '$id' LIMIT 1") or die($mysqli->error);
    $Dati = $resultStream->fetch_row() or die($resultStream->error);
    if (sizeof($Dati) > 0) {

        $mysqli->close();
        return $Dati;
    } else {
        $mysqli->close();
        return array();
    }
}
function research($ricerca)
{
    $ricerca = strtolower($ricerca);
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $result = $mysqli->query("SELECT * FROM film_stream Where Titolo like '$ricerca%'");

    $films = array();
    $i = 0;
    foreach ($result as $row) {
        $films[$i] = $row["Titolo"];
        $i++;
    }
    $mysqli->close();
    return $films;
}
function lista()
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die($mysqli->error);
    $records = $mysqli->query("SELECT * FROM lista where ID_User = " . $_SESSION['user_id']) or die($mysqli->error);

    if (sizeof($records->fetch_assoc()) > 0) {
        $i = 0;
        foreach ($records as $row) {
            $films[$i] = $row["Titolo"];
            $i++;
        }
    } else {
        $films = array();
    }


    $mysqli->close();
    return $films;
}


function addLista($Titolo)
{
    $dati = take_film_stream($Titolo);
    $user = null;
    $film = null;
    if (sizeof($dati) > 0) {
        $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
        $search = $mysqli->prepare("SELECT * from lista where ID_User = ? AND ID_Film = ? LIMIT 1;") or die($mysqli->error);
        $search->bind_param("ii", $user, $film);
        $user = $_SESSION['user_id'];
        $film = $dati["ID_Film"];
        $search->execute() or die("Error description: " . $search->error);
        if (!$search->fetch()) {
            $stmt = $mysqli->prepare("INSERT INTO lista values (?,?);") or die($mysqli->error);
            $stmt->bind_param("ii", $user, $film);
            $stmt->execute() or die("Error description: " . $stmt->error);
            $stmt->close();
        }
        $search->close();
        $mysqli->close();
    }
}
function removeLista($Titolo)
{
    $dati = take_film_stream($Titolo);
    $user = null;
    $film = null;
    if (sizeof($dati) > 0) {
        $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
        $search = $mysqli->prepare("SELECT * from lista where ID_User = ? AND ID_Film = ? LIMIT 1;") or die($mysqli->error);
        $search->bind_param("ii", $user, $film);
        $user = $_SESSION['user_id'];
        $film = $dati["ID_Film"];
        $search->execute() or die("Error description: " . $search->error);
        if ($search->fetch()) {
            $stmt = $mysqli->prepare("DELETE from lista where ID_User = ? AND ID_Film = ?;") or die($mysqli->error);
            $stmt->bind_param("ii", $user, $film);
            $stmt->execute() or die("Error description: " . $stmt->error);
            $stmt->close();
        }
        $search->close();
        $mysqli->close();
    }
}
function prendi_orari($id, $preciso = null)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    if ($preciso == NULL) {
        $Dati = array();
        $resultStream = $mysqli->query("SELECT * FROM timetable where ID_Film = $id") or die($mysqli->error);
        $i = 0;
        foreach ($resultStream as $row) {
            $Dati[$i] = $row;
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
function Prenotato($ID_TIME)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $Dati = array();
    $resultStream = $mysqli->query("SELECT posto FROM biglietto where ID_TimeTable = $ID_TIME;") or die($mysqli->error);
    $Biglietti = $resultStream->fetch_array(MYSQLI_NUM);
    return $Biglietti;
}
function biglietti($id = null)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    if ($id == NULL) {
        $resultStream = $mysqli->query("SELECT * FROM biglietto where ID_User = " . $_SESSION["user_id"] . ";") or die($mysqli->error);
        $Biglietti = array();
        $i = 0;
        foreach ($resultStream as $row) {
            $Biglietti[$i] = $row;
            $i++;
        }
        $mysqli->close();
        return $Biglietti;
    } else {
        $resultStream = $mysqli->query("SELECT * FROM biglietto where ID_User = " . $_SESSION["user_id"] . " AND ID_Ticket = $id;") or die($mysqli->error);
        $Biglietto = $resultStream->fetch_row();
        $mysqli->close();
        return $Biglietto;
    }
}
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
function prenota($id, $posto)
{
    $mysqli = new mysqli("localhost", User, pwd, "cinema_mat") or die('Could not connect to server.');
    $result = $mysqli->query("SELECT COUNT(ID_Ticket) FROM biglietto where posto = '$posto' AND ID_TimeTable = $id;") or die($mysqli->error);
    $rows = $result->fetch_row() or false;
    if ($rows[0] == "0" || $rows == false) {
        $reg = $mysqli->prepare("INSERT INTO biglietto (ID_User,posto,ID_TimeTable) VALUES (?,'$posto',$id);") or die($mysqli->error);
        $user = NULL;
        $reg->bind_param("i", $user);
        $user = $_SESSION["user_id"];
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
