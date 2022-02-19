<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Luka Jikia 79ad7eaa</title>
</head>
<body>
	<?php 
		// var_dump($_GET);

		$guess = $_GET;
		$answer = 32;

		// array_key_exists('guess', $guess);

		$value = array_values($guess);
		
		// $values = count($guess);
		// print_r($value);

		if (empty($guess)){
			echo "Missing guess parameter";
		} elseif (empty($value[0])) {
			echo "Your guess is too short";
		} elseif (!(is_numeric($value[0]))) {
			echo "Your guess is not a number";
		} elseif ($value[0] < $answer) {
			echo "Your guess is too low";
		} elseif ($value[0] > $answer) {
			echo "Your guess is too high";
		} elseif ($value[0] == $answer) {
			echo "Congratulations - You are right";
		}

	 ?>
</body>
</html>