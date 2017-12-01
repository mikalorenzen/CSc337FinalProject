<!DOCTYPE html>

<!-- 
Author: Mika Lorenzen
File Name: scoreboard.php
-->
<html>
<head>
<meta charset="UTF-8">
<title>Scoreboard</title>
<link href="styles.css" type="text/css" rel="stylesheet">
</head>
<body>
<?php session_start (); ?>
	<h1>
		<b><i>All Scores</i></b>
	</h1>
	<br>
	<br>
	<?php
if (isset($_SESSION['registerError']))
    echo $_SESSION['registerError'];
?>
</body>