<?php
//
// Author: Mika Lorenzen
// File name: controller.php
// Acts as the go between the view and the model.
//
include 'DatabaseAdaptor.php';

session_start();

unset($_SESSION['loginError']);
unset($_SESSION['registerError']);
unset($_SESSION['scoreboardError']);

// Login Attempt
if (isset($_POST['LoginUsername']) && isset($_POST['LoginPassword'])) {
    $username = htmlspecialchars($_POST['LoginUsername']);
    $password = htmlspecialchars($_POST['LoginPassword']);
    $result = $theDBA->loginAttempt($username, $password);
    if ($result) {
        $_SESSION['user'] = $_POST['LoginUsername'];
        header('Location: game.php');
    } else {
        $_SESSION['loginError'] = 'Invalid credentials.';
        header('Location: login.php');
    }
}

// Reset session on log out
if (isset($_POST['Logout']) && $_POST['Logout'] === 'Log Out') {
    session_destroy();
    header('Location: game.php');
}

// Register Attempt
if (isset($_POST['RegisterUsername']) && isset($_POST['RegisterPassword'])) {
    $username = htmlspecialchars($_POST['RegisterUsername']);
    $password = htmlspecialchars($_POST['RegisterPassword']);
    $result = $theDBA->registerAttempt($username, $password);
    if ($result) {
        header('Location: game.php');
    } else {
        $_SESSION['registerError'] = 'Username already is in use.';
        header('Location: register.php');
    }
}

if (isset($_GET['getPuzzleInitial'])) {
    $arr = $theDBA->getPuzzleInitial($_GET['getPuzzleInitial']);
    echo json_encode($arr);
}

if (isset($_GET['getPuzzleCompleted'])) {
    $arr = $theDBA->getPuzzleCompleted($_GET['getPuzzleCompleted']);
    echo json_encode($arr);
}

// Scoreboard entry Attempt
if (($_SESSION['user']) != null && isset($_GET['logTime'])) {
    $theDBA->logTime($_GET['logTime'], $_SESSION['user']);
}

if (isset($_GET['scores'])) {
     $arr = $theDBA->getScores();
     echo json_encode($arr);
 }
?>