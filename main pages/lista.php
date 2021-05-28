<?  
require "../utility/Functions.php";
sec_session_start();
if(isset($_POST["list"]) && isset($_POST["film"])){
    if($_POST["list"] == "true"){
        addLista($_POST["film"]);
    }if($_POST["list"] == "false"){
        removeLista($_POST["film"]);
    }
}