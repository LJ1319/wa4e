<?php
  var_dump($_POST);

  $oldguess = '';
  $message = false;
  $answer = 32;

  if ( isset($_POST['guess']) ) {
    $oldguess = $_POST['guess'];

    if ( strlen($oldguess) < 1 ) {
      $message = "Your guess is too short";
    } elseif ( !(is_numeric($oldguess)) ) {
      $message = "Your guess is not a number";
    } elseif ( $oldguess < $answer) {
      $message = "Your guess is too low";
    } elseif ( $oldguess > $answer) {
      $message = "Your guess is too high";
    } else {
      $message = "Congratulations - You are right";
    }
  } elseif ( !(isset($_GET['guess'])) ) {
    $message = 'Missing guess parameter';
  }
 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
 <head>
   <meta charset="utf-8">
   <title>Luka Jikia Guessing Game</title>
 </head>
 <body style="font-family: sans-serif;">
   <p>Guessing game...</p>
   <?php
      if ( $message !== false ) {
        echo("<p>$message</p>\n");
      }
    ?>

    <form method="post">
      <p><label for="guess">Input Guess</label>
        <input type="text" name="guess" id="guess" size="40"
        value="<?= htmlentities($oldguess) ?>"/>
      </p>
      <input type="submit" />
    </form>
 </body>
</html>
