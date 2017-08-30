<?php

/**
* This is for the Current application
*/

//namespace myApp\App;

/**
* Database Utility Class
*/

class DButil
{
	
	function __construct($argument)
	{
		//echo 'DButil constructor '."$argument !";
	}

    public function getConnection($db):PDO{
    	//$db = $this->db;
	    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
	    // not necessary but useful
	    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

	    return $pdo;
    }
}


?>