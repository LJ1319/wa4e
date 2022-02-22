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

	function validateEdu() {
		for ($i = 1; $i <= 9; $i++) { 
			if ( !isset($_POST['edu_year'.$i]) ) continue;
			if ( !isset($_POST['edu_school'.$i]) ) continue;

			$edu_year = $_POST['edu_year'.$i];
			$edu_school = $_POST['edu_school'.$i];

			if ( strlen($edu_year) == 0 || strlen($edu_school) == 0 ) {
				return "All fields are required";
			} 

			if ( !is_numeric($edu_year) ) {
				return "Education year must be numeric";
			}

		}
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

	function loadEdu($pdo, $profile_id) {
		$stmt = $pdo->prepare("SELECT year, name FROM education JOIN institution ON education.institution_id = institution.institution_id WHERE profile_id = :prof ORDER BY rank");
		$stmt->execute(array(':prof' => $profile_id));
		$educations = $stmt->fetchAll(PDO::FETCH_ASSOC);
		return $educations;
	}


	function insertPositions($pdo, $profile_id) {
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
	}

	function insertEducations($pdo, $profile_id) {
		$rank = 1;
		for ($i = 1; $i <= 9 ; $i++) { 
			if ( !isset($_POST['edu_year'.$i]) ) continue;
			if ( !isset($_POST['edu_school'.$i]) ) continue;

			$edu_year = $_POST['edu_year'.$i];
			$edu_school = $_POST['edu_school'.$i];

			$institution_id = false;
			$stmt = $pdo->prepare("SELECT institution_id FROM institution WHERE name = :name");
			$stmt->execute(array(':name' => $edu_school));
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			if ( $row !== false ) $institution_id = $row['institution_id'];


			if ( $institution_id === false ) {
				$stmt = $pdo->prepare("INSERT INTO institution (name) VALUES (:name)");
				$stmt->execute(array(':name' => $edu_school));
				$institution_id = $pdo->lastInsertId();
			}

			$stmt = $pdo->prepare("INSERT INTO education (profile_id, rank, year, institution_id) VALUES (:pid, :rank, :year, :iid)");
			$stmt->execute(array(
				':pid' => $profile_id,
				':rank' => $rank,
				':year' => $edu_year,
				':iid' => $institution_id
			));
			$rank++;
		}
		
	}
?>