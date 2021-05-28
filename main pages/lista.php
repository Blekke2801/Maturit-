<?  
require "../utility/Functions.php";
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (strpos($url, 'Home.php') === false) {
    header("Location:Home.php");
}
sec_session_start();
if(isset($_POST["list"]) && isset($_POST["film"])){
    if($_POST["list"] == "true"){
        addLista($_POST["film"]);
    }if($_POST["list"] == "false"){
        removeLista($_POST["film"]);
    }
}