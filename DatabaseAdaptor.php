<?php

//
// Author: Mika Lorenzen
// File name: DatabaseAdaptor.php
//
class DatabaseAdaptor
{

    // The instance variable used in every one of the functions in class DatbaseAdaptor
    private $DB;

    // Make a connection to an existing database named 'imdb_small' that has table actors
    public function __construct()
    {
        $db = 'mysql:dbname=quotes; charset=utf8; host=127.0.0.1';
        $user = 'root';
        $password = '';
        
        try {
            $this->DB = new PDO($db, $user, $password);
            $this->DB->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo ('Error establishing Connection');
            exit();
        }
    }

    public function registerAttempt($username, $password)
    {
        $stmt = $this->DB->prepare("SELECT * from users where username = '" . $username . "'");
        $stmt->execute();
        
        // If the result of the statement was more than zero then the username exists, and that's an error
        if ($stmt->rowCount() < 1) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->DB->prepare("insert into users (username, hash) values ('" . $username . "','" . $hash . "')");
            $stmt->execute();
            return true;
        } else {
            return false;
        }
    }

    public function loginAttempt($username, $password)
    {
        $stmt = $this->DB->prepare("SELECT * from users where username = '" . $username . "'");
        $stmt->execute();
        
        // If the result of the statement was more than zero (should just be one) row, then the username exists
        if ($stmt->rowCount() > 0) {
            // Now check the hash
            $stmt = $this->DB->prepare("SELECT hash from users where username = '" . $username . "'");
            $stmt->execute();
            $arr = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (password_verify($password, $arr[0]['hash']))
                return true;
            else
                return false;
        } else {
            return false;
        }
    }

    // Return all records as an associative array, using the users table
    public function getUsers()
    {
        $stmt = $this->DB->prepare("select user, hash from users");
        $stmt->execute();
        // fetchall returns all records in the set as an array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addQuoteAttempt($quote, $author)
    {
        // Replace ' with \'
        $readiedQuote = addslashes($quote);
        
        $stmt = $this->DB->prepare("insert into quotations (quote, author, rating, flagged) values ('" . $readiedQuote . "','" . $author . "','0','0')");
        $stmt->execute();
        return true;
    }

    public function flag($id)
    {
        $stmt = $this->DB->prepare("UPDATE quotations SET flagged='1' where id='" . $id . "'");
        $stmt->execute();
    }

    public function unflagAll()
    {
        $stmt = $this->DB->prepare("UPDATE quotations SET flagged='0' where flagged='1'");
        $stmt->execute();
    }

    public function incrementRating($id)
    {
        $stmt = $this->DB->prepare("UPDATE quotations SET rating+='1' where id='" . $id . "'");
        $stmt->execute();
    }
    
    public function decrementRating($id)
    {
        $stmt = $this->DB->prepare("UPDATE quotations SET rating-='1' where id='" . $id . "'");
        $stmt->execute();
    }

    // Return all non-flagged records as an associative array, using the quotations table
    public function getQuotations()
    {
        $stmt = $this->DB->prepare("select id, quote, author, rating from quotations where flagged = 0");
        $stmt->execute();
        // fetchall returns all records in the set as an array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} // End class DatabaseAdaptor

$theDBA = new DatabaseAdaptor();
?>