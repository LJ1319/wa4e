<?php

	require_once "pdo.php";
	session_start();

	if ( !isset($_SESSION['name']) ) {
		die('ACCESS DENIED');
	}

	if ( isset($_POST['cancel']) ) {
		header("Location: index.php");
		return;
	}

	
	if ( isset($_SESSION['user_id']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ) {

		if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ) {
			$_SESSION['error'] = "All fields are required";
			header("Location: add.php");
			return;
		} else {
			$_SESSION['success'] = "added";
		}


		$sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su)";

		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':uid' => $_SESSION['user_id'],
			':fn' => $_POST['first_name'],
			':ln' => $_POST['last_name'],
			':em' => $_POST['email'],
			':he' => $_POST['headline'],
			':su' => $_POST['summary']
		));

		if ( isset($_SESSION['success']) ) {
			header("Location: index.php");
			return;
		}
	}
	
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Luka Jikia Add Page</title>
	<?php require_once "bootstrap.php"; ?>
</head>
<body>
	<div class="container">

		<h2>Add a new Auto</h2>

		<?php

			if ( isset($_SESSION['error']) ) {
			    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
			    unset($_SESSION['error']);
			}

		 ?>
		
		<form method="POST">
			<label for="fn">First Name</label>
			<input type="text" name="first_name" id="fn"><br/>

			<label for="ln">Last name</label>
			<input type="text" name="last_name" id="ln"><br/>

			<label for="em">Email</label>
			<input type="text" name="email" id="em"><br/>

			<label for="he">Headline</label>
			<input type="text" name="headline" id="he"><br/>

			<label for="su">Summary</label><br/>
			<textarea name="summary" id="su" rows="8" cols="80"></textarea><br/>
			

			<input type="submit" value="Add" name="add">
			<input type="submit" name="cancel" value="Cancel">
		</form>
	</div>
</body>
</html>
