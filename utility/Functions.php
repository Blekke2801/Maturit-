<?php
DEFINE ("User","root");
define("pwd","");
function checkbrute($user_id, $ip)
{
    $mysqli = new mysqli("localhost",User, pwd, "cinema_mat") or die('Could not connect to server.');
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
    $mysqli = new mysqli("localhost",User, pwd, "cinema_mat") or die('Could not connect to server.');
    //premium è true, pwd ancora da crypto
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
}
function login($email, $password, $cookie)
{
    $mysqli = new mysqli("localhost",User, pwd, "cinema_mat") or die('Could not connect to server.');
    // Usando statement sql 'prepared' non sarà possibile attuare un attacco di tipo SQL injection.
    // ...ma noi non lo usiamo!
    $sql = "SELECT ID_User,mail,Password FROM utente WHERE mail = '$email' LIMIT 1";
    if ($result = $mysqli->query($sql)) {
        $row = $result->fetch_array();
        
        // recupera il risultato della query e lo memorizza nelle relative variabili.
        $user_id = $row["ID_User"];
        $username = $row["mail"];
        $db_password = $row["Password"];
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
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // ci proteggiamo da un attacco XSS
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = md5($password . $user_browser, false);
                    if (isset($_POST["remember"])) {
                        setcookie("user", array($username, $password), time() + (86400 * 7));
                    }
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
    $mysqli = new mysqli("localhost",User, pwd, "cinema_mat") or die('Could not connect to server.');
    // Verifica che tutte le variabili di sessione siano impostate correttamente
    if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
        $sql = "SELECT Password FROM utente WHERE ID_User = $user_id LIMIT 1";
        if ($result = $mysqli->query($sql)) {
            if ($result->num_rows == 1) { // se l'utente esiste
                $row = $result->fetch_array();
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
function take_film_stream()
{
    $mysqli = new mysqli("localhost",User, pwd, "cinema_mat") or die('Could not connect to server.');
    $result = $mysqli->query("SELECT * FROM film_stream");
    $films = array();
    $i = 0;
    foreach ($result as $row) {
        $films[$i] = $row["Titolo"];
    }
    $mysqli->close();
    return $films;
}
function take_film_prenotabili()
{
    $mysqli = new mysqli("localhost",User, pwd, "cinema_mat") or die('Could not connect to server.');
    $result = $mysqli->query("SELECT * FROM film_prenotabili");
    $films = array();
    $i = 0;
    foreach ($result as $row) {
        $films[$i] = $row["Titolo"];
    }
    $mysqli->close();
    return $films;
}
function searchFilm($Titolo)
{
    $mysqli = new mysqli("localhost",User, pwd, "cinema_mat") or die('Could not connect to server.');
    $AllFilms = array();
    if (!isset($_GET["page"])) {
        $AllFilms = take_film_prenotabili();
    } else if ($_GET["page"] == "prenota") {
        $AllFilms = take_film_stream();
    } else {
        $mysqli->close();
        return false;
    }
    if (array_search($Titolo, $AllFilms) === true) {
        $mysqli->close();
        return true;
    } else {
        $mysqli->close();
        return false;
    }
}
?>