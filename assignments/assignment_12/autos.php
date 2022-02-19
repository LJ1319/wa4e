<?php 
	require_once "pdo.php";

	// print_r($_GET);

	if ( !isset($_GET['name']) ) {
		die("Name parameter missing");
	}

	if ( isset($_POST['logout']) ) {
		header("Location: index.php");
		return;
	}

	$failure = false;
	$success = false;

	if ( isset($_POST['make']) && isset($_POST['year']) 
		 && isset($_POST['mileage']) ) {
		if ( strlen($_POST['make']) < 1 ) {
			$failure = "Make is required";
		}
		if ( !( is_numeric($_POST['mileage']) && is_numeric($_POST['year']) ) ) {
			$failure = "Mileage and year must be numeric";
		} elseif ( strlen($_POST['make']) > 1 && strlen($_POST['year']) > 1 && strlen($_POST['mileage']) > 1 ) {
			$success = "Record inserted";
		}

		$sql = "INSERT INTO autos (make, year, mileage) VALUES (:make, :year, :mileage)";
		// echo("<pre>\n".$sql."\n</pre>\n");

		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':make' => $_POST['make'],
			':year' => $_POST['year'],
			':mileage' => $_POST['mileage']
		));

		
	}

	$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

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

 			<h1>Tracking Autos for <?= htmlentities($_GET['name']) ?></h1>

 			<?php
				if ( $failure !== false ) {
				    echo('<p style="color: red;">'.htmlentities($failure)."</p>\n");
				}

				if ( $success !== false ) {
					echo('<p style="color: green;">'.htmlentities($success)."</p>\n");
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
				<input type="submit" name="logout" value="Log Out">
			</form>

			<h2>Automobiles</h2>
			<ul>
				<?php 
					foreach ( $rows as $row ) {
						echo "<li>";
						echo(htmlentities($row['year'] . " "));
						echo(htmlentities($row['make'] . " / " ));
						echo(htmlentities($row['mileage']));
						echo("</li\n>");
					}
				 ?>
			</ul>
			
	</div>
 </body>
 </html>