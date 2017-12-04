<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="style.css" type="text/css" rel="stylesheet" />
<title>Sudoku</title>
</head>
<body onload="redraw()" onkeydown="keyDown(event)">
<div class="overall">
	<canvas id="gameScreen" width="576" height="576" onmousemove="mouseMove(event)" onclick="mouseClick(event)"></canvas>
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
context.textAlign = "center";
context.textBaseline = "middle";
var example = [[0,8,0,0,4,0,0,7,5],
    [6,2,0,5,0,9,0,0,0],
    [0,4,0,0,0,1,0,0,0],
    [5,0,0,0,0,7,4,0,0],
    [7,0,0,1,0,8,0,0,9],
    [0,0,2,3,0,0,0,0,7],
    [0,0,0,4,0,0,0,6,0],
    [0,0,0,6,0,2,0,9,1],
    [2,9,0,0,1,0,0,8,0]];
						
var editable = [];
for(var i = 0; i < boardSize;i++){
	r = [];
	for(var j = 0; j < boardSize; j++){
		if(example[i][j] == 0){
			r.push(true);
		}else{
			r.push(false);
		}
	}
	editable.push(r);
}
var example_solution = [[1,8,9,2,4,6,3,7,5],
    [6,2,7,5,3,9,1,4,8],
    [3,4,5,8,7,1,9,2,6],
    [5,1,8,9,6,7,4,3,2],
    [7,3,4,1,2,8,6,5,9],
    [9,6,2,3,5,4,8,1,7],
    [8,7,1,4,9,5,2,6,3],
    [4,5,3,6,8,2,7,9,1],
    [2,9,6,7,1,3,5,8,4]];
var cursor = {x: -1, y: -1, color: "blue", selectedColor: "darkblue", selected: false};
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
			context.lineWidth = 1;
			drawLine(0, i*gridSize, gridSize * boardSize, i*gridSize);
			drawLine(i*gridSize, 0, i*gridSize, gridSize * boardSize);
		}
		
	}
}

function redraw(){
	context.clearRect(0, 0, gameScreen.width, gameScreen.height);
	drawBoard();
	readLevel(example);
}
// Draw puzzle
function readLevel(arr){
	for(var y=0; y < boardSize; y++){
		for(var x=0; x < boardSize; x++){
			if(arr[y][x] != "0"){
				if(editable[y][x]){
					context.fillStyle = "green";
					context.font = "36px Arial";
				}else{
					context.fillStyle = "black";
					context.font = "50px Arial";
				}
				context.fillText(arr[y][x].toString(), (x+0.5) * gridSize, (y+0.5) * gridSize);
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
// called when mouse clicked, lock down the cursor's position
function mouseClick(event){
 	var pos = mousePos(event);
	if(invalidPos(pos)){
		return
	}
	if(cursor.selected){
		cursor.selected = false;
		cursor.x = pos.x;
		cursor.y = pos.y;
		redraw();
		drawCursor();
	}else if(editable[cursor.y][cursor.x]){
		cursor.selected = true;
		drawCursor();
	}
}
function keyDown(event){
	console.log(event.keyCode);
	if(!cursor.selected){
		return;
	}
	n = parseInt(String.fromCharCode(event.keyCode));
	if(n >= 1 && n <= boardSize){
		example[cursor.y][cursor.x] = n;
	}
	redraw();
	drawCursor();
}
// called when mouse position changed, refresh cursor location
function mouseMove(event){
	if(cursor.selected){
		return;
	}
 	var pos = mousePos(event);
	if(pos.x != cursor.x || pos.y != cursor.y){
		if(!invalidPos(pos)){
			cursor.x = pos.x;
			cursor.y = pos.y;
			redraw();
			drawCursor();
		}
	}
}
// get mouse position on grid
function mousePos(event){
	var rect = gameScreen.getBoundingClientRect();
	return {
		x: Math.floor((event.clientX - rect.left)/gridSize),
		y: Math.floor((event.clientY - rect.top)/gridSize)};
}

// return true if pos is a invalid position on board
function invalidPos(pos){
	return pos.x < 0 || pos.x >= boardSize || pos.y < 0 || pos.y >= boardSize;
}

// draw unlocked cursor
function drawCursor(){
	if(cursor.selected){
		context.strokeStyle = cursor.selectedColor;
		
	}else{
		context.strokeStyle = cursor.color;
	}
	context.lineWidth = "6";
	context.strokeRect(cursor.x * gridSize+3, cursor.y * gridSize+3, gridSize-6, gridSize-6);

}
</script>
</body>
</html>