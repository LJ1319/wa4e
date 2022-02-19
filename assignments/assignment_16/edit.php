<?php 
	
	require_once "pdo.php";
	session_start();

	$stmt = $pdo->prepare("SELECT profile_id, user_id, first_name, last_name, email, headline, summary FROM profile WHERE profile_id = :xyz");
	$stmt->execute(array(":xyz" => $_GET['profile_id']));
	$row = $stmt->fetch(PDO::FETCH_ASSOC);
	

	if ( $_SESSION['user_id'] !== $row['user_id']) {
		die("ACCESS DENIED");
	}
	
	if ( isset($_POST['cancel']) ) {
		header("Location: index.php");
		return;
	}

	if ( isset($_SESSION['user_id']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ) {

		if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ) {
			$_SESSION['error'] = "Missing data";
			header("Location: edit.php?profile_id=".$row['profile_id']);
			return;
		} 


		$sql = "UPDATE profile SET user_id = :uid, first_name = :fn, last_name = :ln, email = :em, headline = :he, summary = :su WHERE profile_id = :profile_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':uid' => $_SESSION['user_id'],
			':fn' => $_POST['first_name'],
			':ln' => $_POST['last_name'],
			':em' => $_POST['email'],
			':he' => $_POST['headline'],
			':su' => $_POST['summary'],
			'profile_id' => $row['profile_id']
		));
		
		$_SESSION['success'] = 'Record updated';
	    header( 'Location: index.php' ) ;
	    return;

	}


	if ( ! isset($_SESSION['user_id']) ) {
	  $_SESSION['error'] = "Missing user_id";
	  header('Location: index.php');
	  return;
	}


	if ( $row == false ) {
		$_SESSION['error'] = "Bad value for profile_id";
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
			$uid = htmlentities($_SESSION['user_id']);
			$fn = htmlentities($row['first_name']); 
			$ln = htmlentities($row['last_name']);
			$em = htmlentities($row['email']);
			$he = htmlentities($row['headline']);
			$su = htmlentities($row['summary']);
			$profile_id = $row['profile_id'];
		 ?>

		<h2>Editing Profile N<?= htmlentities($profile_id) ?></h2>

		<?php 

			if ( isset($_SESSION['error']) ) {
			    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
			    unset($_SESSION['error']);
			}

		 ?>


		<form method="POST">
			<label for="fn">First Name</label>
			<input type="text" name="first_name" id="fn" value="<?= $fn ?>"><br/>

			<label for="ln">Last name</label>
			<input type="text" name="last_name" id="ln" value="<?= $ln ?>"><br/>

			<label for="em">Email</label>
			<input type="text" name="email" id="em" value="<?= $em ?>"><br/>

			<label for="he">Headline</label>
			<input type="text" name="headline" id="he" value="<?= $he ?>"><br/>

			<label for="su">Summary</label><br/>
			<textarea name="summary" id="su" rows="8" cols="80"><?= $su ?></textarea><br/>
			
			<input type="hidden" name="profile_id" value="<?= $profile_id ?>">

			<input type="submit" value="Save" name="save">
			<input type="submit" name="cancel" value="Cancel">
		</form>

	</div>
</body>
</html>