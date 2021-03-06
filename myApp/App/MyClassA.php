<?php

//namespace myApp\App;
/**
* A sample Class
*/


//class MyClassA extends AnotherClass
class MyClassA
{
	
	public $var = 'a default value';
	
	function __construct($argument)
	{
		echo 'MyClassA constructor '."$argument !";
	}
	
	public function displayVar() {
        echo $this->var;
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