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
}


?>