<?php
require_once "../utility/Functions.php";
sec_session_start();
if (isset($_GET["list"]) && isset($_GET["film"])) {

    if ($_GET["list"] == "true") { //aggiunge
        addLista($_GET["film"]);
    } else if ($_GET["list"] == "false") { //toglie
        removeLista($_GET["film"]);
    }else {
        header("Location: Home.php");
    }
} else
    header("Location: Home.php");
?>