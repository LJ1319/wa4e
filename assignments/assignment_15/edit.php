<?php 
	
	require_once "pdo.php";
	session_start();

	if ( isset($_POST['cancel']) ) {
		header("Location: index.php");
		return;
	}

	if ( isset($_POST['make']) && isset($_POST['model']) && isset($_POST['year']) && isset($_POST['mileage']) && isset($_POST['autos_id']) ) {

		if ( strlen($_POST['make']) < 1 || strlen($_POST['model']) < 1 || strlen($_POST['year']) < 1 || strlen($_POST['mileage']) < 1 ) {
			$_SESSION['error'] = "Missing data";
			header("Location: edit.php?autos_id=".$_POST['autos_id']);
			return;
		}

		if ( !(is_numeric($_POST['mileage']) && is_numeric($_POST['year'])) ) {
			$_SESSION['error'] = "Invalid data";
			header("Location: edit.php?autos_id=".$_POST['autos_id']);
			return;
		}

		$sql = "UPDATE autos SET make = :make, model = :model, year = :year, mileage = :mileage WHERE autos_id = :autos_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':make' => $_POST['make'],
			':model' => $_POST['model'],
			':year' => $_POST['year'],
			':mileage' => $_POST['mileage'],
			':autos_id' => $_POST['autos_id']
		));
		$_SESSION['success'] = "Record updated";
		header("Location: index.php");
		return;

	}	

	if ( !isset($_GET['autos_id']) ) {
		$_SESSION['error'] = "Missing autos_id";
		header("Location: index.php");
		return;
	}

	$stmt = $pdo->prepare("SELECT * FROM autos WHERE autos_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['autos_id']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SESSION['error'] = "Bad value for autos_id";
		header("Location: index.php");
		return;
	}

 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Luka Jikia Edit Page</title>
	<?php require_once "bootstrap.php"; ?>
</head>
<body>
	<div class="container">
		
		<?php

			$mk = htmlentities($row['make']);
			$mo = htmlentities($row['model']);
			$yr = htmlentities($row['year']);
			$mi = htmlentities($row['mileage']);
			$autos_id = $row['autos_id'];
			
		 ?>

		<h2>Editing Auto N<?= htmlentities($autos_id) ?></h2>

		<?php 

			if ( isset($_SESSION['error']) ) {
			    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
			    unset($_SESSION['error']);
			}

		 ?>

		<form method="POST">
			<label for="mk">Make</label>
			<input type="text" name="make" id="mk" value="<?= $mk ?>"><br/>

			<label for="mo">Model</label>
			<input type="text" name="model" id="mo" value="<?= $mo ?>"><br/>

			<label for="yr">Year</label>
			<input type="text" name="year" id="yr" value="<?= $yr ?>"><br/>

			<label for="mi">Mileage</label>
			<input type="text" name="mileage" id="mi" value="<?= $mi ?>"><br/>

			<input type="hidden" name="autos_id" value="<?= $autos_id ?>">	

			<input type="submit" value="Save" name="save">
			<input type="submit" name="cancel" value="Cancel">
		</form>
	</div>
</body>
</html>