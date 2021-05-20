<?php
function connect()
{
    define("HOST", "localhost"); // E' il server a cui ti vuoi connettere.
    define("USER", "root"); // E' l'utente con cui ti collegherai al DB.
    define("PASSWORD", ""); // Password di accesso al DB.
    define("DATABASE", "cinema_mat"); // Nome del database.
    // mysqli_connect corrisponde a mysql_connect + mysql_select_db
    $mysqli = new mysqli(HOST, USER, PASSWORD, DATABASE) or die('Could not connect to server.');
    return $mysqli();
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
    $mysqli = connect();
    //premium è true, pwd ancora da crypto
    $reg = $mysqli->prepare("INSERT INTO `utente` (`Mail`, `Password`, `Nome`, `Cognome`, `Data_Birth`, `Cln_Imp`, `Free_Premium`) VALUES ('$email', ?, '$nome', '$cognome', '$birth', 1, ?)");
    $reg->bind_param("sb", $pwd, $tariffa);
    if ($tariffa == "false") {
        $tariffa = 0;
    } else {
        $tariffa = 1;
    }
    $pwd = md5($pwd, FALSE); //pwd crypto md5
    $reg->execute();
    $reg->close();
    $mysqli->close();
}
function login($email, $password, $mysqli)
{
    $mysqli = connect();
    // Usando statement sql 'prepared' non sarà possibile attuare un attacco di tipo SQL injection.
    // ...ma noi non lo usiamo!
    $sql = "SELECT id,mail,password FROM members WHERE mail = '$email' LIMIT 1";
    if ($result = $mysqli->query($mysqli, $sql)) {
        $row = $mysqli->fetch_array($result);
        // recupera il risultato della query e lo memorizza nelle relative variabili.
        $user_id = $row["id"];
        $username = $row["username"];
        $db_password = $row["password"];
        $password = md5($password, FALSE); // codifica la password usando una chiave univoca.
        if (mysqli_num_rows($result) == 1) { // se l'utente esiste
            // verifichiamo che non sia disabilitato in seguito all'esecuzione di troppi tentativi di accesso errati.

            if ($db_password == $password) { // Verifica che la password memorizzata nel database corrisponda alla password fornita dall'utente.
                // Password corretta!
                $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.

                $user_id = preg_replace("/[^0-9]+/", "", $user_id); // ci proteggiamo da un attacco XSS
                $_SESSION['user_id'] = $user_id;
                $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // ci proteggiamo da un attacco XSS
                $_SESSION['username'] = $username;
                $_SESSION['login_string'] = hash('sha512', $password . $user_browser);
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

                echo "INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')";
                echo "<br><br>";

                $mysqli->query($mysqli, "INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')");
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
function LoginCookie($mysqli)
{
    if (isset($_COOKIE["user"])) {
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Recupero il parametro 'user-agent' relativo all'utente corrente.
        $_SESSION['username'] = $_COOKIE["user"][0];
        $_SESSION['login_string'] = $_COOKIE["user"][1] . hash('sha512', $user_browser);
        return true;
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
    header('Location: ./');
}
//Crea la funzione 'login_check':
function login_check($mysqli)
{
    // Verifica che tutte le variabili di sessione siano impostate correttamente
    if (isset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['login_string'])) {
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
        $user_browser = $_SERVER['HTTP_USER_AGENT']; // reperisce la stringa 'user-agent' dell'utente.
        $sql = "SELECT password FROM members WHERE id = $user_id LIMIT 1";
        if ($result = mysqli_query($mysqli, $sql)) {
            if (mysqli_num_rows($result) == 1) { // se l'utente esiste
                $row = mysqli_fetch_array($result);

                $password = $row["password"]; // recupera le variabili dal risultato ottenuto.
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
    $mysqli = connect();
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
    $mysqli = connect();
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
    $mysqli = connect();
    $AllFilms = array();
    if (!isset($_GET["page"])) {
        $AllFilms = take_film_prenotabili($mysqli);
    } else if ($_GET["page"] == "prenota") {
        $AllFilms = take_film_stream($mysqli);
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
