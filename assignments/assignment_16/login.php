<?php 
	
	require_once "pdo.php";
	session_start();


	if ( isset($_POST['cancel']) ) {
		header("Location: index.php");
		return;
	}

	$salt = 'XyZzy12*_';

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
			$stmt = $pdo->prepare("SELECT user_id, name FROM users WHERE email = :em AND password = :pw");
			$stmt->execute(array(':em' => $_POST['email'], ':pw' => $check));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);

			if ( $row !== false ) {

				$_SESSION['name'] = $row['name'];
				$_SESSION['user_id'] = $row['user_id'];
				header("Location: index.php");
				return;

			} else {
				
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
			
			Email <input type="text" name="email" id="email_id"><br/>
			Password <input type="text" name="pass" id="pass_id"><br/>

			<input type="submit" name="Log In" value="Log In" onclick="return doValidate();">
			<input type="submit" name="cancel" value="Cancel">

		</form>

	</div>

	<script type="text/javascript" src="script.js"></script>
</body>
</html>