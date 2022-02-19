<?php 
	
	require_once "pdo.php";
	session_start();

	if ( isset($_POST['delete']) && isset($_POST['autos_id']) ) {
		$sql = "DELETE FROM autos WHERE autos_id = :zip";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(':zip' => $_POST['autos_id']));
		$_SESSION['success'] = "Record deleted";
		header("Location: index.php");
		return; 
	}

	if ( !isset($_GET['autos_id']) ) {
		$_SESSION['error'] = "Missing autos_id";
		header("Location: index.php");
		return;
	}

	$stmt = $pdo->prepare("SELECT autos_id FROM autos WHERE autos_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['autos_id']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	if ( $row === false ) {
		$_SERVER['error'] = "Bad value for autos_id";
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

 			if ( isset($_SESSION['error']) ) {
 			    echo('<p style="color: green;">'.htmlentities($_SESSION['error'])."</p>\n");
 			    unset($_SESSION['error']);
 			}

 		 ?>

 		<h2>Deleting Auto N<?= htmlentities($row['autos_id']) ?></h2>

 		<form method="POST">
 			
 			<input type="hidden" name="autos_id" value="<?= $row['autos_id'] ?>">	

 			<input type="submit" value="Delete" name="delete">
 			<input type="submit" name="cancel" value="Cancel">
 		</form>
 	</div>
 </body>
 </html>