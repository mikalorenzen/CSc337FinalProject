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
context.font = "50px Arial";
var example = [[0,8,0,0,4,0,0,7,5],
    [6,2,0,5,0,9,0,0,0],
    [0,4,0,0,0,1,0,0,0],
    [5,0,0,0,0,7,4,0,0],
    [7,0,0,1,0,8,0,0,9],
    [0,0,2,3,0,0,0,0,7],
    [0,0,0,4,0,0,0,6,0],
    [0,0,0,6,0,2,0,9,1],
    [2,9,0,0,1,0,0,8,0]];
						
var example_solution = [[1,8,9,2,4,6,3,7,5],
    [6,2,7,5,3,9,1,4,8],
    [3,4,5,8,7,1,9,2,6],
    [5,1,8,9,6,7,4,3,2],
    [7,3,4,1,2,8,6,5,9],
    [9,6,2,3,5,4,8,1,7],
    [8,7,1,4,9,5,2,6,3],
    [4,5,3,6,8,2,7,9,1],
    [2,9,6,7,1,3,5,8,4]];
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
	readLevel(example);
}

// Draw puzzle
function readLevel(arr){
	for(var y=0; y < boardSize; y++){
		for(var x=0; x < boardSize; x++){
			context.fillStyle = "black";
			if(arr[y][x] != "0"){
				context.fillText(arr[y][x].toString(), x * gridSize + 18, (y+1) * gridSize - 14);
			}
			//document.log("x:" + x.toString() + " y:" + y.toString() + " value:" + arr[y][x]);
		}
	}
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