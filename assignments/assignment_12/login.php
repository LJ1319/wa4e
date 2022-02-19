<?php 

	if ( isset($_POST['cancel']) ) {
		header("Location: index.php");
		return;
	}

	$salt = 'XyZzy12*_';
	$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123 

	$failure = false;

	if ( isset($_POST['who']) && isset($_POST['pass']) ) {
		$email = $_POST['who'];
		if ( strlen($email) < 1 || strlen($_POST['pass']) < 1 ) {
			$failure = "Email and password are required";
		} else if ( strpos($email, '@') == false ) {
			$failure = "Email must have an at-sign (@)";
		} else {
			$check = hash('md5', $salt.$_POST['pass']);
			if ( $check == $stored_hash) {
				error_log("Login success ".$_POST['who']);
				header("Location: autos.php?name=".urlencode($_POST['who']));
				return;
			} else {
				error_log("Login fail ".$_POST['who']." $check");
				$failure = "Incorrect password";
			}
		}
	}

 ?>
 <!DOCTYPE html>
<html>
	<head>
	<?php require_once "bootstrap.php"; ?>
	<title>Luka Jikia Login Page</title>
	<?php require_once "bootstrap.php"; ?>
	</head>
	<body>
		<div class="container">
			<h1>Please Log In</h1>

			<?php
			if ( $failure !== false ) {
			    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
			}
			?>

			<form method="POST">
				<label for="name">User Name</label>
				<input type="text" name="who" id="name"><br/>

				<label for="pass">Password</label>
				<input type="password" name="pass" id="pass"><br/>

				<input type="submit" value="Log In">
				<input type="submit" name="cancel" value="Cancel">
			</form>
			
		</div>
	</body>