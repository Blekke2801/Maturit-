<?php
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (strpos($url, 'Home.php') === false) {

    require "../../utility/connect.php";
    require "../../utility/Functions.php";
    if (!login_check($mysqli)) {
        header("Location:../Home.php");
    }
}
?>