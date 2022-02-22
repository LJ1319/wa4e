<?php

	require_once "pdo.php";
	require_once "util.php";
	session_start();

	$stmt = $pdo->query("SELECT profile_id, user_id, first_name, last_name, headline FROM profile");
	$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Luka Jikia Resume Registry</title>
	<?php require_once "bootstrap.php"; ?>


</head>
<body>

	<div class="container">

		<h1>Luka Jikia Resume Registry</h1>

		<?php

			if ( !isset($_SESSION['name']) ) {

				echo('<a href="login.php">Please log in</a>');

				flashMessages();

				if ( empty($rows) ) {
					echo("<p>No rows found</p>");
				} else {

					echo('<table border="1">'."\n");
					echo("<tr><th>");
					echo("Name"."</th><th>");
					echo("Headline"."</th></tr>");

					foreach ( $rows as $row ) {

					    echo("<tr><td>");
		 ?>
   					    <a href="view.php?profile_id=<?= $row['profile_id'] ?>">
   					     	<?= htmlentities($row['first_name']." ".$row['last_name']) ?>
   					    </a>

   		<?php
					    echo("</td><td>");
					    echo(htmlentities($row['headline']));
					    echo("</td></tr>");

					}
					echo("</table>");
				}

			} else {

				flashMessages();

				if ( empty($rows) ) {
					echo("<p>No rows found</p>");
				} else {

					echo('<table border="1">'."\n");
					echo("<tr><th>");
					echo("Name"."</th><th>");
					echo("Headline"."</th><th>");
					echo("Action"."</th></tr>");

					foreach ( $rows as $row ) {

					    echo("<tr><td>");
		 ?>
					    <a href="view.php?profile_id=<?= $row['profile_id'] ?>">
					     	<?= htmlentities($row['first_name']." ".$row['last_name']) ?>
					    </a>
		<?php
					    echo("</td><td>");
					    echo(htmlentities($row['headline']));
					    echo("</td><td>");

					    if ( $_SESSION['user_id'] === $row['user_id']) {

					    	echo('<a href="form.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
					    	echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');

					    } else {
					    	echo("Not owned");
					    }
					    echo("</td></tr>");

					}
					echo("</table>");
				}

				echo('<a href="forms.php">Add New Entry</a> | ');
				echo('<a href="logout.php">Logout</a>');
			}
		 ?>

	</div>




</body>
</html>
