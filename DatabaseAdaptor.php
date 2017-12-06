<?php

//
// Author: Mika Lorenzen
// File name: DatabaseAdaptor.php
//
class DatabaseAdaptor
{

    // The instance variable used in every one of the functions in class DatbaseAdaptor
    private $DB;

    public function __construct()
    {
        $db = 'mysql:dbname=sudoku; charset=utf8; host=127.0.0.1';
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
    
    public function loginAttempt($username, $password)
    {
        $stmt = $this->DB->prepare("SELECT * from users where username = :username");
        $stmt->bindParam ( ':username', $username );
        $stmt->execute();
        
        // If the result of the statement was more than zero (should just be one) row, then the username exists
        if ($stmt->rowCount() > 0) {
            // Now check the hash
            $stmt = $this->DB->prepare("SELECT hash from users where username = :username");
            $stmt->bindParam ( ':username', $username );
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

    public function registerAttempt($username, $password)
    {
        $stmt = $this->DB->prepare("SELECT * from users where username = :username");
        $stmt->bindParam ( ':username', $username );
        $stmt->execute();
        
        // If the result of the statement was more than zero then the username exists, and that's an error
        if ($stmt->rowCount() < 1) {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->DB->prepare("insert into users (username, hash, puzzles_completed, Puzzle_1_Best_Time) values (:username,:hash,'0','0')");
            $stmt->bindParam ( ':username', $username );
            $stmt->bindParam ( ':hash', $hash );
            $stmt->execute();
            return true;
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

    

    // Return all scores using the two tables and a join
    public function getScores()
    {
        $stmt = $this->DB->prepare("SELECT users.username, puzzles.id, puzzles.highscore_time FROM users JOIN puzzles ON users.id = puzzles.highscore_user_id");
        $stmt->execute();
        // fetchall returns all records in the set as an array
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} // End class DatabaseAdaptor

$theDBA = new DatabaseAdaptor();
//echo $theDBA->registerAttempt("admin", "sudoku");
?>