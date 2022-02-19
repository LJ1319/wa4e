<?php 
	
	require_once "pdo.php";
	session_start();

	$stmt = $pdo->query("SELECT autos_id, make, model, year, mileage FROM autos");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// print_r($rows);

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Luka Jikia Autos Database</title>
	<?php require_once "bootstrap.php"; ?>
</head>
<body>

	<div class="container">
		
		<h1>Welcome to the Autos Database</h1>

		<?php if ( !isset($_SESSION['name']) ) { ?>
					<p>
						<a href="login.php">Please log in</a>
					</p>
					<p>
						Attempt to 
						<a href="add.php">add data</a> without logging in
					</p>

		<?php } else { ?>

				<h2>Tracking Autos for <?= htmlentities($_SESSION['name']) ?></h2>

				<?php
					if ( isset($_SESSION['error']) ) {
					    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
					    unset($_SESSION['error']);
					}
					if ( isset($_SESSION['success']) ) {
					    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
					    unset($_SESSION['success']);
					}

					if ( empty($rows) ) {
						echo("<p>No rows found</p>");
					} else {
						echo('<table border="1">'."\n");
						echo("<tr><th>");
						echo("Make"."</th><th>");
						echo("Model"."</th><th>");
						echo("Year"."</th><th>");
						echo("Mileage"."</th><th>");
						echo("Action"."</th></tr>"); 

						foreach ( $rows as $row ) {
		
						    echo("<tr><td>");
						    echo(htmlentities($row['make']));
						    echo("</td><td>");
						    echo(htmlentities($row['model']));
						    echo("</td><td>");
						    echo(htmlentities($row['year']));
						    echo("</td><td>");
						    echo(htmlentities($row['mileage']));
						    echo("</td><td>");

						    echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
						    echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
						    echo("</td></tr>");

						}
						echo("</table>");
					}
				 ?>
				

				<a href="add.php">Add New Entry</a> |
				<a href="logout.php">Logout</a>

		<?php	} ?>

	</div>

	
</body>
</html>