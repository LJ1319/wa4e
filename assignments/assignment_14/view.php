<?php 

	require_once "pdo.php";
	session_start();
	

	if ( !isset($_SESSION['name']) ) {
		die("Not logged in");
	}

	$stmt = $pdo->query("SELECT make, year, mileage FROM autos");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Luka Jikia View Database</title>
	<?php require_once "bootstrap.php"; ?>
</head>
<body>
	<div class="container">

		<h1>Tracking Autos for <?= htmlentities($_SESSION['name']) ?></h1>			

		<?php 

			if ( isset($_SESSION['success']) ) {
					echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
					unset($_SESSION['success']);
				}

		 ?>

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
		
		<a href="add.php">Add New</a> |
		<a href="logout.php">Logout</a>

</div>
</body>
</html>