<!DOCTYPE html>

<!-- 
Author: Mika Lorenzen
File Name: login.php
-->
<html>
<head>
<meta charset="UTF-8">
<title>Log In</title>
<link href="style.css" type="text/css" rel="stylesheet">
</head>
<body>
<?php session_start ();
if (! isset($_SESSION['user'])) {
    echo "<h1>
		      <b><i>Log In</i></b>
	      </h1>
                <form action='controller.php' method='POST'>
        		Enter Username: <input type='text' name='LoginUsername'
        			class='textfield' required> <br> Enter Password: <input
        			type='password' name='LoginPassword' class='textfield' required> <br>
        		<input type='submit' name='LoginSubmit' value='Log In'>
        	    </form><br><br>";
} else {
    echo "<h1>
		      <b><i>Log Out</i></b>
	     </h1>
                <form action='controller.php' method='POST'>
        	    <input type='submit' name='Logout' value='Log Out'>
        	    </form>";
}
if (isset($_SESSION['loginError']))
    echo $_SESSION['loginError'];
?>
</body>