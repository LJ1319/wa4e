<?php
  require_once "pdo.php";

  // GET Parameter user_id=1

  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :xyz");
  $stmt->execute(array(":pizza" => $_GET['user_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  if ( $row === false ) {
      echo("<p>user_id not found</p>\n");
  } else {
      echo("<p>user_id found</p>\n");
  }
 ?>