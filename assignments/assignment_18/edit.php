<?php
    require_once "pdo.php";
    require_once "util.php";

    SESSION_START();
    if (!isset($_SESSION['name'])) {
        die('<h1>ACCESS DENIED</h1>');
    }

    if (!isset($_REQUEST['profile_id'])) {
        $_SESSION['error'] ="Missing profile_id parameter";
        header('Location: index.php');
        return;
    }


    $stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :prof AND user_id = :uid");
    $stmt->execute(array(
        ':prof' => $_REQUEST['profile_id'],
        'uid' => $_SESSION['user_id']
    ));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row === false) {
        $_SESSION['error'] = 'Could not load profile';
        header("Location: index.php");
        return;
    }

    $profile_id = htmlentities($_REQUEST['profile_id']);
    $first_name = htmlentities($row['first_name']);
    $last_name = htmlentities($row['last_name']);
    $email = htmlentities($row['email']);
    $headline = htmlentities($row['headline']);
    $summary = htmlentities($row['summary']);


    if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) && isset($_POST['profile_id'])) {

        if (strlen($_POST['first_name']) <1 || strlen($_POST['last_name']) <1 ||    strlen($_POST['email']) <1 || strlen($_POST['headline']) <1 ||  strlen($_POST['summary']) < 1 || $_POST['profile_id'] < 1) {
            $_SESSION['error'] = "All fields are required";
            header("Location: edit.php?profile_id={$_POST['profile_id']}");
            return;
      }

      $username = htmlentities($_POST['email']);
      if (strpos($username, '@') === false) {
          $_SESSION['error'] = "Email must have an at-sign (@)";
          header("Location: edit.php?profile_id={$_POST['profile_id']}");
          return;
      }


        // validate position entries if present
        $msg = validatePos();
        if ( is_string($msg)) {
            $_SESSION['error'] = $msg;
            header("Location: edit.php?profile_id=" . $_REQUEST['profile_id']);
            return;
        }

        $sql = "UPDATE profile SET first_name = :first_name, last_name = :last_name, email = :email, headline = :headline, summary = :summary WHERE profile_id = :p_id AND user_id = :uid";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
            ':first_name' => $_POST['first_name'],
            'uid' => $_SESSION['user_id'],
            ':last_name' => $_POST['last_name'],
            ':email' => $_POST['email'],
            ':headline' => $_POST['headline'],
            ':summary' => $_POST['summary'],
            ':p_id' => $_REQUEST['profile_id'])
        );


        $stmt = $pdo->prepare('DELETE FROM position WHERE profile_id = :pid');
        $stmt->execute(array(
          ':pid' => $_REQUEST['profile_id']
        ));
        insertPositions($pdo, $_REQUEST['profile_id']);

        $msg = validateEdu();
        if ( is_string($msg)) {
            $_SESSION['error'] = $msg;
            header("Location: edit.php?profile_id=" . $_REQUEST['profile_id']);
            return;
        }
        $stmt = $pdo->prepare("DELETE FROM education WHERE profile_id = :pid");
        $stmt->execute(array(':pid' => $_REQUEST['profile_id']));

        insertEducations($pdo, $_REQUEST['profile_id']);
}

$positions = loadPos($pdo, $_REQUEST['profile_id']);
$schools = loadEdu($pdo, $_REQUEST['profile_id']);


if (isset($_POST['submit'])) {
    $_SESSION['success'] = "Profile updated";
    header("Location: index.php");
    return;
}

 ?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Luka Jikia Edit Page</title>
    <?php require_once "bootstrap.php"; ?>
  </head>
  <body>


  <div class="container">

    <h1>Editing Profile for <?php echo $_SESSION['name']; ?> </h1>
    <?php

    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">'. $_SESSION['error']."</p>");
        unset($_SESSION['error']);
    }

     ?>


    <form class=""  method="post">
      <p>First Name:
        <input type="text" name="first_name" value="<?php echo $first_name; ?>" size="60"></p>
      <p>Last Name:
        <input type="text" name="last_name" value="<?php echo $last_name; ?>" size="60"></p>
      <p>Email:
        <input type="text" name="email" value="<?php echo $email; ?>" size="30"></p>
      <p>Headline:
        <input type="text" name="headline" value="<?php echo $headline; ?>" size="80"></p>
        <p>Summary:
          <br>
          <textarea name="summary" rows="8" cols="80"><?php echo $summary; ?></textarea></p>

            <?php

                $countPos = 0;
                echo('<p>Position: <input type="submit" id="addPos" value="+">' . "\n");
                echo('<div id="position_fields">' . "\n");
                if ( count($positions) > 0 ) {
                    foreach ($positions as $position) {
                        $countPos++;
                        echo('<div id="position'.$countPos.'">'."\n");
                        echo('<p>Year: <input type="text" name="year'.$countPos.'" ');
                        echo('value="'.$position['year'].'"/>'."\n");
                        echo('<input type="button" value="-" ');
                        echo('onclick="$(\'#position'.$countPos.'\').remove();return false;">'."\n");
                        echo("</p>\n");
                        echo('<textarea name="descr'.$countPos.'" rows="8" cols="80">'."\n");
                        echo(htmlentities($position['description'])."\n");
                        echo("\n</textarea>\n</div>\n");
                    }
                } 
                echo("</div></p>\n");
             ?>

            <?php   


                $countEdu = 0;
                echo('<p>Education: <input type="submit" id="addEdu" value="+">' . "\n");
                echo('<div id="edu_fields">' . "\n");
                if ( count($schools) > 0 ) {
                    foreach ($schools as $school) {
                        // print_r($school);
                        $countEdu++;
                        echo('<div id="edu'.$countEdu.'">'."\n");
                        echo('<p>Year: <input type="text" name="edu_year'.$countEdu.'" ');
                        echo('value="'.$school['year'].'"/>'."\n");
                        echo('<input type="button" value="-" ');
                        echo('onclick="$(\'#edu'.$countEdu.'\').remove();return false;">'."\n");
                        echo("</p>\n");
                        echo('<p>School <input type="text" size="80" name="edu_school'.$countEdu.'" class="school" value="'.htmlentities($school['name']).'"/>'."\n");
                        echo("\n</div>\n");
                    }
                }
                
                echo("</div></p>\n");

             ?>


        <input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>">
        <p><input type="submit" name="submit" value="Save">
        <button type="button" name="button"><a href="index.php">Cancel</a></button></p>
    </form>
  </div>



  <script type="text/javascript">

  countPos = <?= $countPos; ?>;
  countEdu = <?= $countEdu; ?>;


  $(document).ready(function(){
    window.console && console.log('Document ready called');
    $('#addPos').click(function(event){
    // http://api.jquery.com/event.preventdefault/
    event.preventDefault();
    if ( countPos >= 9 ) {
        alert("Maximum of nine position entries exceeded");
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

    $('#addEdu').click(function(event){
        event.preventDefault();
        if ( countEdu >= 9 ) {
            arert("Maximum of nine education entries exceeded");
            return;
    }
    countEdu++;
    window.console && console.log("Adding education"+countEdu);

    var source = $("#edu-template").html();
    $('#edu_fields').append(source.replace(/@COUNT@/g,countEdu));

        $('.school').autocomplete({
            source: "school.php"
        });
    });

    $('.school').autocomplete({
        source: "school.php"
    });

  });

  </script>

  <script id="edu-template" type="text">
    <div id="edu@COUNT@">
        <p>
            Year: <input type="text" name="edu_year@COUNT@" value="" />
            <input type="button" value="-" onclick="$('#edu@COUNT@').remove(); return false;"><br>
        </p>
        <p>
            School: <input type="text" size="80" name="edu_school@COUNT@" class="school" value="" /> 
        </p>
    </div>
  </script>        


  </body>
</html>
