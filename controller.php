<?php
//
// Author: Mika Lorenzen
// File name: controller.php
// Acts as the go between the view and the model.
//
include 'DatabaseAdaptor.php';

session_start();

unset($_SESSION['registerError']);
unset($_SESSION['loginError']);
unset($_SESSION['addQuoteError']);

// Register Attempt
if (isset($_POST['RegisterUsername']) && isset($_POST['RegisterPassword'])) {
    $result = $theDBA->registerAttempt($_POST['RegisterUsername'], $_POST['RegisterPassword']);
    if ($result) {
        header('Location: index.php');
    } else {
        $_SESSION['registerError'] = 'Username already is in use.';
        header('Location: register.php');
    }
}

// Login Attempt
if (isset($_POST['LoginUsername']) && isset($_POST['LoginPassword'])) {
    $result = $theDBA->loginAttempt($_POST['LoginUsername'], $_POST['LoginPassword']);
    if ($result) {
        $_SESSION['user'] = $_POST['LoginUsername'];
        header('Location: index.php');
    } else {
        $_SESSION['loginError'] = 'Invalid credentials.';
        header('Location: login.php');
    }
}
// Reset session on log out
if (isset($_POST['Logout']) && $_POST['Logout'] === 'Log Out') {
    session_destroy();
    header('Location: index.php');
}

// Add Quote Attempt
if (isset($_POST['AddQuoteQuotation']) && isset($_POST['AddQuoteAuthor'])) {
    $result = $theDBA->addQuoteAttempt($_POST['AddQuoteQuotation'], $_POST['AddQuoteAuthor']);
    if ($result) {
        header('Location: index.php');
    } else {
        $_SESSION['addQuoteError'] = 'Invalid entries.';
        header('Location: addquote.php');
    }
}

// Flag a quote
if (isset($_POST['Flag'])) {
    $theDBA->flag($_POST['Flag']);
    header('Location: index.php');
}

// Unflag all quotes
if (isset($_POST['UnflagAll']) && $_POST['UnflagAll'] === 'Unflag All Quotes') {
    $theDBA->unflagAll();
    header('Location: index.php');
}

// Rate a quote
if (isset($_POST['Rating'])) {
    $theDBA->incrementRating($_POST['Rating']);
    header('Location: index.php');
}

if (isset($_GET['quotations'])) {
    $arr = $theDBA->getQuotations();
    echo json_encode($arr);
}
?>