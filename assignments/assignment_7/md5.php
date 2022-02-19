<?php
var_dump($_GET);

$md5 = "Not computed";
if ( isset($_GET['md5']) ) {
    $md5 = hash('md5', $_GET['md5']);
}
?>
<!DOCTYPE html>
<head><title>Luka Jikia MD5</title></head>
<body>
<h1>MD5 Maker</h1>
<p>MD5: <?= htmlentities($md5); ?></p>
<form>
<input type="text" name="md5" size="40" />
<input type="submit" value="Compute MD5"/>
</form>
<p><a href="md5.php">Reset</a></p>
<p><a href="index.php">Back to Cracking</a></p>
</body>
</html>
