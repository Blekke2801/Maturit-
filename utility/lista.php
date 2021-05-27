<?  
require "Functions.php";
$url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];

if (strpos($url, 'Home.php') === false) {

    require "../../utility/connect.php";
    require "../../utility/Functions.php";
    header("Location:../main pages/Home.php");
}
sec_session_start();
if(isset($_POST["list"]) && $_POST["list"] == "true" && isset($_POST["film"])){
    
}