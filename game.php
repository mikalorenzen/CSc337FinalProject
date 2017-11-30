<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="style.css" type="text/css" rel="stylesheet" />
<title>Sudoku</title>
</head>
<body onload="drawBoard()">
<div class="overall">
	<canvas id="gameScreen" width="576" height="576"></canvas>
	<div class="UI">
		<h2>Level 1</h2>
		<p>Login for more levels!</p>
		<br><br><br>
		<div class="buttons">login</div><br><br><br>
		<div class="buttons">register</div><br><br><br>
		<div class="buttons">scoreboard</div><br><br><br>
		<p>Time</p>
	</div>
</div>
<script>
var boardSize = 9;
var gridSize = 64;
var gameScreen = document.getElementById("gameScreen");
var context=gameScreen.getContext("2d");
// Draw a boardSize x boardSize grid board on canvas 
function drawBoard(){
	var board = Array(boardSize);
	for(var i=0; i < boardSize; i++){
		board[i] = Array(boardSize);
		if(i!=0){
			if(i % 3 == 0){
				context.strokeStyle="red";
			}else{
				context.strokeStyle="black";
			}
			drawLine(0, i*gridSize, gridSize * boardSize, i*gridSize);
			drawLine(i*gridSize, 0, i*gridSize, gridSize * boardSize);
		}
		
	}
}
function readLevel(){
	
}
function drawLine(x0, y0, x1, y1, width){
	context.beginPath();
	context.moveTo(x0, y0);
	context.lineTo(x1, y1);
	context.stroke();
}
</script>
</body>
</html>