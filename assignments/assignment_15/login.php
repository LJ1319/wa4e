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
			if ( $check == $stored_hash ) {
				error_log("Login success ".$_POST['email']);
				$_SESSION['name'] = $_POST['email'];
				header("Location: index.php?name=".urlencode($_POST['email']));
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

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<title>Luka Jikia Login Page</title>
	<?php require_once "bootstrap.php" ?>


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
			
			User Name <input type="text" name="email"><br/>
			Password <input type="text" name="pass"><br/>

			<input type="submit" name="Log In" value="Log In">
			<input type="submit" name="cancel" value="Cancel">

		</form>

	</div>
</body>
</html>