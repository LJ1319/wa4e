<?php

	require_once "pdo.php";
	require_once "util.php";

	session_start();

	$stmt = $pdo->prepare("SELECT profile_id, user_id, first_name, last_name, email, headline, summary FROM profile WHERE profile_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['profile_id']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);


	if ( !isset($_GET['profile_id']) ) {
		$_SESSION['error'] = "Missing profile_id";
		header("Location: index.php");
		return;
	}


	if ( $row === false ) {
		$_SERVER['error'] = "Bad value for profile_id";
		header("Location: index.php");
		return;
	}

	$positions = loadPos($pdo, $_REQUEST['profile_id']);

 ?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Luka Jikia Delete Page</title>
	<?php require_once "bootstrap.php"; ?>
</head>
<body>
	<div class="container">

		<?php

			flashMessages();

		 ?>

		<h2>Profile N<?= htmlentities($row['profile_id']) ?> Information</h2>


		<p>First Name: <?= htmlentities($row['first_name']) ?></p>
		<p>Last Name: <?= htmlentities($row['last_name']) ?></p>
		<p>Email: <?= htmlentities($row['email']) ?></p>
		<p>Headline: <?= htmlentities($row['headline']) ?></p>
		<p>Summary: <?= htmlentities($row['summary']) ?></p>

		<p>Position</p>
		<ul>
			<?php foreach( $positions as $position) { ?>
				<li><?= htmlentities($position['year'].": ".$position['description']); ?></li>
			<?php	} ?>	
			
		</ul>

		


		<a href="index.php">Done</a>

	</div>
</body>
</html>
