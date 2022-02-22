<?php 
	
	require_once "pdo.php";
	session_start();
	header("Content-type: application/json; charset=utf-8");

	$rows = array();

	$prof = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :pid");
	$prof->execute(array(':pid' => $_REQUEST['profile_id']));
	while ( $row = $prof->fetch(PDO::FETCH_ASSOC) ) {
		$rows['profile'] = $row;
	}

	$pos = $pdo->prepare("SELECT * FROM position WHERE profile_id = :pid");
	$pos->execute(array(':pid' => $_REQUEST['profile_id']));
	while ( $row = $pos->fetch(PDO::FETCH_ASSOC) ) {
		$rows['positions'][] = $row;
	}

	$edu = $pdo->prepare("SELECT education.year, education.institution_id, education.profile_id, institution.name, institution.institution_id FROM education JOIN institution ON education.institution_id = institution.institution_id WHERE profile_id = :pid");
	$edu->execute(array(':pid' => $_REQUEST['profile_id']));
	while ( $row = $edu->fetch(PDO::FETCH_ASSOC) ) {
		$rows['schools'][] = $row;
	} 

	
	echo json_encode($rows, JSON_PRETTY_PRINT);