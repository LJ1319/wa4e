<?php

	session_start();

	if ( isset($_POST['cancel']) ) {
		header("Location: index.php");
		return;
	}

	$salt = 'XyZzy12*_';
	$stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';  // Pw is php123


	if ( isset($_POST['email']) && isset($_POST['pass']) ) {
		unset($_SESSION['name']);
		$email = $_POST['email'];
		if ( strlen($email) < 1 || strlen($_POST['pass']) < 1 ) {
			$_SESSION['error'] = "Email and password are required";
			header("Location: login.php");
			return;
		} else if ( strpos($email, '@') == false ) {
			$_SESSION['error'] = "Email must have an at-sign (@)";
		} else {
			$check = hash('md5', $salt.$_POST['pass']);
			if ( $check == $stored_hash) {
				error_log("Login success ".$_POST['email']);
				$_SESSION['name'] = $_POST['email'];
				header("Location: view.php?name=".urlencode($_POST['email']));
				return;
			} else {
				error_log("Login fail ".$_POST['email']." $check");
				$_SESSION['error'] = "Incorrect password";
				header("Location: login.php");
				return;
			}
		}

	}

 ?>
<!DOCTYPE html>
<html lang="en">
	<head>

	<title>Luka Jikia Login Page</title>
	<?php require_once "bootstrap.php"; ?>
	
	</head>
	<body>
		<div class="container">
			<h1>Please Log In</h1>

			<?php

				if ( isset($_SESSION['error']) ) {
					echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
					unset($_SESSION['error']);
				}
			?>

			<form method="POST">
				<label for="email">User Name</label>
				<input type="text" name="email" id="email"><br/>

				<label for="pass">Password</label>
				<input type="password" name="pass" id="pass"><br/>

				<input type="submit" value="Log In">
				<input type="submit" name="cancel" value="Cancel">
			</form>

		</div>
	</body>
