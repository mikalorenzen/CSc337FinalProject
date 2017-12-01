<!DOCTYPE html>

<!-- 
Author: Mika Lorenzen
File Name: register.php
-->
<html>
<head>
<meta charset="UTF-8">
<title>Register</title>
<link href="styles.css" type="text/css" rel="stylesheet">
</head>
<body>
<?php session_start (); ?>
	<h1>
		<b><i>Register An Account</i></b>
	</h1>
	<form action="controller.php" method="POST">
		Enter Username: <input type="text" name="RegisterUsername"
			class='textfield' required> <br> Enter Password: <input
			type="password" name="RegisterPassword" class='textfield' required> <br>
		<input type="submit" name="RegisterSubmit" value="Register">
	</form>
	<br>
	<br>
	<?php
if (isset($_SESSION['registerError']))
    echo $_SESSION['registerError'];
?>
</body>