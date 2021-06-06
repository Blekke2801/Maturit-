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
	<title>Login</title>
	<link rel="stylesheet" href="../utility/css/style_log.css">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="../js/controls.js"></script>
	<link rel="icon" href="../img/favicon.ico" />
	<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.1/css/all.css" integrity="sha384-gfdkjb5BdAXd+lj+gudLWI+BXq4IuLW5IT+brZEZsLFm++aCMlF1V92rMkPaX4PP" crossorigin="anonymous">
</head>

<body <?php if ($error) echo "onload='errorReg()'"; //funzione js per avvisare che la registrazione ha fallito
		?>>
	<div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="login_card">
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
						<img src="../img/logo_login.jpg" style="border-radius:50%" alt="Logo">
					</div>
				</div>
				<div class="subnav">
					<a href="Home.php"><img src="../img/home.png" title="Home"></a>
					<a href="Home.php"><img src="../img/register.png" title="Registrati!"></a>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form action="Home.php" method="post">
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user"></i></span>
							</div>
							<input type="text" name="user" class="form-control input_user" value="<?php if ($register) echo htmlentities($_POST["mail"]); ?>" placeholder="username">
						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i></span>
							</div>
							<input type="password" name="pwd" id="pwd" class="form-control input_pass" value="" placeholder="password">
						</div>
						<div class="form-group">
							<div class="custom-control custom-checkbox">
								<input type="checkbox" name="remember" value="1" class="custom-control-input" id="customControlInline" checked>
								<label class="custom-control-label" for="customControlInline">Remember me</label>
							</div>
						</div>
						<div class="d-flex justify-content-center mt-3 login_container">
							<button type="submit" name="button" class="btn login_btn">Login</button>
						</div>
					</form>
				</div>

				<div class="mt-4">
					<div class="d-flex justify-content-center links">
						Don't have an account? <a href="register.php" class="ml-2">Sign Up</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</body>

</html>