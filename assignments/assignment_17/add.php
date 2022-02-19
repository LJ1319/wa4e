<?php

	require_once "pdo.php";
	require_once "util.php";

	session_start();

	if ( !isset($_SESSION['name']) ) {
		die('ACCESS DENIED');
	}

	if ( isset($_POST['cancel']) ) {
		header("Location: index.php");
		return;
	}

	
	if ( isset($_SESSION['user_id']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) ) {

		if ( strlen($_POST['first_name']) < 1 || strlen($_POST['last_name']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['headline']) < 1 || strlen($_POST['summary']) < 1 ) {
			$_SESSION['error'] = "All fields are required";
			header("Location: add.php");
			return;
		} 


		$msg = validatePos();
		if ( is_string($msg) ) {
			$_SESSION['error'] = $msg;
			header("Location: add.php");
			return;
		}


		$sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary) VALUES (:uid, :fn, :ln, :em, :he, :su)";

		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':uid' => $_SESSION['user_id'],
			':fn' => $_POST['first_name'],
			':ln' => $_POST['last_name'],
			':em' => $_POST['email'],
			':he' => $_POST['headline'],
			':su' => $_POST['summary']
		));
		$profile_id = $pdo->lastInsertId();


		$rank = 1;
		for ($i = 1; $i <= 9 ; $i++) { 
			if ( !isset($_POST['year'.$i]) ) continue;
			if ( !isset($_POST['descr'.$i]) ) continue;

			$year = $_POST['year'.$i];
			$descr = $_POST['descr'.$i];

			$stmt = $pdo->prepare("INSERT INTO position (profile_id, rank, year, description) VALUES (:pid, :rank, :year, :descr)");
			$stmt->execute(array(
				':pid' => $profile_id,
				':rank' => $rank,
				':year' => $year,
				':descr' => $descr
			));
			$rank++;

		}


		$_SESSION['success'] = "added";	
		header("Location: index.php");
		return;
	}
	

 ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Luka Jikia Add Page</title>
	<?php require_once "bootstrap.php"; ?>
</head>
<body>
	<div class="container">

		<h2>Add a new Profile</h2>

		<?php

			flashMessages();

		 ?>
		
		<form method="POST">
			<label for="fn">First Name</label>
			<input type="text" name="first_name" id="fn"><br/>

			<label for="ln">Last name</label>
			<input type="text" name="last_name" id="ln"><br/>

			<label for="em">Email</label>
			<input type="text" name="email" id="em"><br/>

			<label for="he">Headline</label>
			<input type="text" name="headline" id="he"><br/>

			<label for="su">Summary</label><br/>
			<textarea name="summary" id="su" rows="8" cols="80"></textarea><br/>
			
			<p>
				Position: <input type="submit" id="addPos" value="+">
				<div id="position_fields"></div>
			</p>

			<input type="submit" value="Add" name="add">
			<input type="submit" name="cancel" value="Cancel">
		</form>


		<script type="text/javascript">
			countPos = 0;

			$(document).ready(function(){
				window.console && console.log('Document ready called');
				$('#addPos').click(function(event){
					event.preventDefault();
					if ( countPos >= 9 ) {
						alert("Maximum of nine position exceeded");
						return;
					}
					countPos++;
					window.console && console.log("Adding position "+countPos);
					$('#position_fields').append(
						'<div id="position'+countPos+'"> \
				            <p>Year: <input type="text" name="year'+countPos+'" value="" /> \
				            <input type="button" value="-" \
				                onclick="$(\'#position'+countPos+'\').remove();return false;"></p> \
				            <textarea name="descr'+countPos+'" rows="8" cols="80"></textarea>\
			            </div>');
				});
			});

		</script>

	</div>
</body>
</html>
