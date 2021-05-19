<?php
define("HOST", "localhost"); // E' il server a cui ti vuoi connettere.
define("USER", "root"); // E' l'utente con cui ti collegherai al DB.
define("PASSWORD", ""); // Password di accesso al DB.
define("DATABASE", "cinema_mat"); // Nome del database.
// mysqli_connect corrisponde a mysql_connect + mysql_select_db
$mysqli = new mysqli( HOST, USER, PASSWORD, DATABASE) or die('Could not connect to server.' );
?>