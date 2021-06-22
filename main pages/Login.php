<?php
require("../utility/Functions.php");
sec_session_start();
$register = false;
$error = false;
if (isset($_POST["nome"])) { // se true vuol dire che l'utente arriva dalla pagina di registrazione, quindi lo registra
	$register = true;
	if (!register($_POST["nome"], $_POST["cognome"], $_POST["mail"], $_POST["pwd"], $_POST["birth"], $_POST["tariffa"])) {
		$error = true;
	}
}
//pagina del login
?>
<!DOCTYPE html>
<html>


<head>
	<link rel="icon" href="../img/favicon.ico" />
	<link rel="stylesheet" href="../utility/css/Login.css">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<title>Sign in</title>
	<script src="../js/controls.js"></script>
</head>

<body <?php if ($error) echo "onload='errorReg()'"; //funzione js per avvisare che la registrazione ha fallito
		?>>
	<div class="subnav">
		<a class="btn" href="Home.php">Home</a>
		<a class="btn" href="register.php">Registrati</a>
	</div>
	</div>
	<div class="main">
		<p class="sign" align="center">Sign in</p>
		<form action="Home.php" class="form1" method="POST">
			<input class="un" type="text" name="user" align="center" value="<?php if ($register) echo htmlentities($_POST["mail"]); ?>" placeholder="Username">
			<input class="pass" type="password" name="pwd" align="center" placeholder="Password">
			<p class="forgot" align="center">Remember me <input type="checkbox" name="remember"></p>
			<button type="submit" class="submit" align="center">Sign in</button>
			<p class="forgot" align="center">Don't have an account? <a href="register.php">Sign Up</a></p>
	</div>
</body>

</html>