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


	if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year'])
		 && isset($_POST['mileage']) ) {

		if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1 ) {
			$_SESSION['error'] = "All fields are required";
			header("Location: add.php");
			return;
		}

		if ( !( is_numeric($_POST['mileage']) && is_numeric($_POST['year']) ) ) {
			$_SESSION['error'] = "Mileage and year must be numeric";
			header("Location: add.php");
			return;
		} elseif ( strlen($_POST['make']) > 1 && strlen($_POST['year']) > 1 && strlen($_POST['mileage']) > 1 ) {
			$_SESSION['success'] = "added";
		} else {
			$_SESSION['error'] = "All fields ar required";
			header("Location: add.php");
			return;
		}

		$sql = "INSERT INTO autos (make, model, year, mileage) VALUES (:make, :model, :year, :mileage)";

		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':make' => $_POST['make'],
			':model' => $_POST['model'],
			':year' => $_POST['year'],
			':mileage' => $_POST['mileage']
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
	<title>Luka Jikia Make Database</title>
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
			<label for="mk">Make</label>
			<input type="text" name="make" id="mk"><br/>

			<label for="mo">Model</label>
			<input type="text" name="model" id="mo"><br/>

			<label for="yr">Year</label>
			<input type="text" name="year" id="yr"><br/>

			<label for="mi">Mileage</label>
			<input type="text" name="mileage" id="mi"><br/>

			<input type="submit" value="Add" name="add">
			<input type="submit" name="cancel" value="Cancel">
		</form>
	</div>
</body>
</html>
