<?php
	function flashMessages() {
		if ( isset($_SESSION['error']) ) {
		    echo '<p style="color:red">'.$_SESSION['error']."</p>\n";
		    unset($_SESSION['error']);
		}

		if ( isset($_SESSION['success']) ) {
		    echo '<p style="color:green">'.$_SESSION['success']."</p>\n";
		    unset($_SESSION['success']);
		}
	}
		
	// function validateProfile() {

	// }

	function validatePos() {
		for ($i = 1; $i <= 9 ; $i++) { 
			if ( !isset($_POST['year'.$i]) ) continue;
			if ( !isset($_POST['descr'.$i]) ) continue;

			$year = $_POST['year'.$i];
			$descr = $_POST['descr'.$i];

			if ( strlen($year) == 0 || strlen($descr) == 0 ) {
				return "All fields are required";
			} 

			if ( !is_numeric($year) ) {
				return "Position year must be numeric";
			}

			
		}
		return true;
	}


	function loadPos($pdo, $profile_id) {
		$stmt = $pdo->prepare('SELECT * FROM position WHERE profile_id = :prof');
		$stmt->execute(array(':prof' => $profile_id));
		$positions = array();
		while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
			$positions[] = $row;
		}	
		return $positions;
	}
?>