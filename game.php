<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<link href="style.css" type="text/css" rel="stylesheet" />
<title>Sudoku</title>
</head>
<body onload="drawBoard()" onkeydown="keyDown(event)">
<?php session_start (); ?>
<div class="overall">
	<canvas id="gameScreen" width="576" height="576" onmousemove="mouseMove(event)" onclick="mouseClick(event)"></canvas>
	<div class="UI">
		<h2>Level 1</h2>
		<p>Login for more levels!</p>
		<br><br>
		<?php
		if (! isset($_SESSION['user'])) {
		    echo
		      "<div class='buttons' onclick='goToLogin()'>login</div><br><br>
		      <div class='buttons' onclick='goToRegister()'>register</div><br><br>
		      <div class='buttons' onclick='goToScoreboard()'>scoreboard</div><br><br>
                <div class='buttons' onclick='start()'>start</div><br><br><br>";
		} else {
		    echo
		    "<div class='buttons' onclick='goToLogin()'>logout</div><br><br>
		     <div class='buttons' onclick='goToScoreboard()'>scoreboard</div><br><br>
             <div class='buttons' onclick='start()'>start</div><br><br><br>";
		}
		?>
	</div>
</div>
<script>
var boardSize = 9;
var gridSize = 64;
var gameScreen = document.getElementById("gameScreen");
var context=gameScreen.getContext("2d");
context.textAlign = "center";
context.textBaseline = "middle";
var puzzles = [];
var editable = [];
var solution = [];
var startTime;
var completionTime;
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
function start(){
	getPuzzleInitial(1);
	console.log(puzzles);
	getPuzzleCompleted(1);
	mouseMove();
	startTime = new Date();
}
function redraw(){
	context.clearRect(0, 0, gameScreen.width, gameScreen.height);
	drawBoard();
	readLevel(puzzles);
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
// this function will fill most of puzzles for debugging purposes
function debugging(){
	for(var i = 0; i < boardSize; i++){
		for(var j = 0; j < boardSize-1; j++){
			if(puzzles[i][j] == 0){
				puzzles[i][j] = solution[i][j];
			}
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
	if(puzzles.length == 0){
		return;
	}
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
	if(!cursor.selected || puzzles.length == 0){
		return;
	}
	n = parseInt(String.fromCharCode(event.keyCode));
	console.log(n);
	if(n >= 1 && n <= boardSize){
		if(!editable[cursor.y][cursor.x]){
			return;
		}
		puzzles[cursor.y][cursor.x] = n;
		redraw();
		drawCursor();
		if(checkWinning()){
			alert("you win!");
		}
	}else{
		switch(event.keyCode){
		case 13:
			debugging();
			break;
		case 37:
			if(cursor.x > 0){
				cursor.x -= 1;
			}
			break;
		case 38:
			if(cursor.y > 0){
				cursor.y -= 1;
			}
			break;
		case 39:
			if(cursor.x < boardSize-1){
				cursor.x += 1;
			}
			break;
		case 40:
			if(cursor.y < boardSize-1){
				cursor.y += 1;
			}
			break;
		default:
			return;
		}
		redraw();
		drawCursor();
	}
}
// called when mouse position changed, refresh cursor location
function mouseMove(event){
	if(cursor.selected || puzzles.length == 0){
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
function checkWinning(){
	for(var i = 0; i < boardSize; i++){
		for(var j = 0; j < boardSize; j++){
			if(puzzles[i][j] != solution[i][j]){
				return false;
			}
		}
	}
	completionTime = new Date();
	completionTime -= startTime;
	completionTime = completionTime / 1000;
	completionTime = parseInt(completionTime);
	console.log(completionTime);
	logTime();
	return true;
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

// these functions handle navigation to other pages 
function goToLogin(){
	alert("Warning! Navigating away from this page will lose your progress on the puzzle!");
	window.location.href = 'login.php';
}

function goToRegister(){
	alert("Warning! Navigating away from this page will lose your progress on the puzzle!");
	window.location.href = 'register.php';
}

function goToScoreboard(){
	alert("Warning! Navigating away from this page will lose your progress on the puzzle!");
	window.location.href = 'scoreboard.php';
}

// these functions populate the arrays puzzles and solution
function getPuzzleInitial(id) {
	var anObj = new XMLHttpRequest();
	anObj.open("GET", "controller.php?getPuzzleInitial=" + id, true);
	anObj.send();
	anObj.onreadystatechange = function() {
		if (anObj.readyState == 4 && anObj.status == 200) {
			var array = JSON.parse(anObj.responseText);
			for(var i = 0; i < 9; i++)
			{
    			var arrayRow = [];
    			for (var j = 0; j < 9; j++)
    			{
    				arrayRow.push(parseInt(array[0]['initial_state'].charAt((i * 9) + j)));
    			}
    			puzzles.push(arrayRow);
			}
		}
		for(var i = 0; i < boardSize;i++){
			r = [];
			for(var j = 0; j < boardSize; j++){
				if(puzzles[i][j] == 0){
					r.push(true);
				}else{
					r.push(false);
				}
			}
			editable.push(r);
		}
	}
}

function getPuzzleCompleted(id) {
	var anObj = new XMLHttpRequest();
	anObj.open("GET", "controller.php?getPuzzleCompleted=" + id, true);
	anObj.send();
	anObj.onreadystatechange = function() {
		if (anObj.readyState == 4 && anObj.status == 200) {
 			var array = JSON.parse(anObj.responseText);
			for(var i = 0; i < 9; i++)
			{
    			var arrayRow = [];
    			for (var j = 0; j < 9; j++)
    			{
    				arrayRow.push(parseInt(array[0]['completed_state'].charAt((i * 9) + j)));
    			}
    			solution.push(arrayRow);
			}
		}
	}
}

function logTime() {
	var anObj = new XMLHttpRequest();
	anObj.open("GET", "controller.php?logTime=" + completionTime, true);
	anObj.send();
}

</script>
</body>
</html>