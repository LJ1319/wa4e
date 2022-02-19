<?php 
	session_start();
 ?>
<html>
	<head></head>
	<body style="font-family: sans-serif;">
		<h1>Cool Application</h1>
		<?php 
			if ( isset($_SESSION['success']) ) {
				echo('<p style="color:green">'.$_SESSION['success']."</p>\n");
				unset($_SESSION['success']);
			}

			// Check if we are logged in!
			if ( ! isset($_SESSION['account']) ) { ?>
				<p>Please <a href="login.php">Log In</a> to start.</p>
			<?php }	else { ?> 
				<p>This is wehere a cool application would be.</p>
				<p>Plase <a href="logout.php">Log Out</a> when you are done.</p>
			<?php } ?>
		 
	</body>
</html>	