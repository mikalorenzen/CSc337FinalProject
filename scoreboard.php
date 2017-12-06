<!DOCTYPE html>

<!-- 
Author: Mika Lorenzen
File Name: scoreboard.php
-->
<html>
<head>
<meta charset="UTF-8">
<title>Scoreboard</title>
<link href="style.css" type="text/css" rel="stylesheet">
</head>
<body onload="getScores()">
<?php session_start (); ?>
	<h1>
		<b><i>All Scores</i></b>
	</h1>
	<br>
	<div id="toChange"></div>

	<script>
		var array = [];

		function getScores() {
			var anObj = new XMLHttpRequest();
			anObj.open("GET", "controller.php?scores=true", true);
			anObj.send();

			anObj.onreadystatechange = function() {
				if (anObj.readyState == 4 && anObj.status == 200) {
					var array = JSON.parse(anObj.responseText);

					var str = "";
					for (i = 0; i < array.length; i++)
					{
							str += "<p class='score'>User: " + array[i]['username'] + " -- Puzzle #"
								+ array[i]['id'] + " -- Best Time Completed: " + array[i]['highscore_time']
								+ " seconds!</p>";
					}
					document.getElementById("toChange").innerHTML = str;
				}
			}
		}
	</script>
</body>