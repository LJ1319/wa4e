<?php

	require_once "pdo.php";
	session_start();
	
	if ( !isset($_SESSION['name']) ) {
		die('Not logged in');
	}

	if ( isset($_POST['cancel']) ) {
		header("Location: view.php");
		return;
	}

	if ( isset($_POST['make']) && isset($_POST['year']) 
		 && isset($_POST['mileage']) ) {
		if ( strlen($_POST['make']) < 1 ) {
			$_SESSION['error'] = "Make is required";
			header("Location: add.php");
			return;
		}
		if ( !( is_numeric($_POST['mileage']) && is_numeric($_POST['year']) ) ) {
			$_SESSION['error'] = "Mileage and year must be numeric";
			header("Location: add.php");
			return;
		} elseif ( strlen($_POST['make']) > 1 && strlen($_POST['year']) > 1 && strlen($_POST['mileage']) > 1 ) {
			$_SESSION['success'] = "Record inserted";
		}

		$sql = "INSERT INTO autos (make, year, mileage) VALUES (:make, :year, :mileage)";

		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':make' => $_POST['make'],
			':year' => $_POST['year'],
			':mileage' => $_POST['mileage']
		));

		if ( isset($_SESSION['success']) ) {
			header("Location: view.php");
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

			<h1>Tracking Autos for <?= htmlentities($_SESSION['name']) ?></h1>
		
			<?php

				if ( isset($_SESSION['error']) ) {
				    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
				    unset($_SESSION['error']);
				}
				
			 ?>


		<form method="POST">
			<label for="mk">Make</label>
			<input type="text" name="make" id="mk"><br/>

			<label for="yr">Year</label>
			<input type="text" name="year" id="yr"><br/>

			<label for="mi">Mileage</label>
			<input type="text" name="mileage" id="mi"><br/>	

			<input type="submit" value="Add">
			<input type="submit" name="cancel" value="Cancel">
		</form>
</div>
</body>
</html>