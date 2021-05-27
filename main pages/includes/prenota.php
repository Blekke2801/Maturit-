<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (strpos($url, 'Home.php') === false) {

    require ("../../utility/Functions.php");
    if (!login_check()) {
        header("Location:../Home.php");
    }
}
echo "<h1 style='color:white'> film in questa settimana </h1>";
?>