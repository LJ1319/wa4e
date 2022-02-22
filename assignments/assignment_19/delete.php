<?php 
	
	require_once "pdo.php";
	require_once "util.php";

	session_start();

	if ( isset($_POST['cancel']) ) {
		header("Location: index.php");
		return;
	}

	$stmt = $pdo->prepare("SELECT profile_id, user_id, first_name, last_name, email, headline, summary FROM profile WHERE profile_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['profile_id']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	

	if ( $_SESSION['user_id'] !== $row['user_id']) {
		die("ACCESS DENIED");
	}

	if ( isset($_POST['delete']) && isset($_POST['profile_id']) ) {
		$sql = "DELETE FROM profile WHERE profile_id = :zip";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(':zip' => $_POST['profile_id']));
		$_SESSION['success'] = "Record deleted";
		header("Location: index.php");
		return; 
	}

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



 		<h2>Deleting Profile N<?= htmlentities($row['profile_id']) ?></h2>

 		<form method="POST">
 			
 			<input type="hidden" name="profile_id" value="<?= $row['profile_id'] ?>">	

 			<input type="submit" value="Delete" name="delete">
 			<input type="submit" name="cancel" value="Cancel">
 		</form>
 	</div>
 </body>
 </html>