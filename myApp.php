<?php 

//
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
//
require 'vendor/autoload.php';
//
//require_once __DIR__.'/myApp/App/MyClassA.php';
//require_once '/myApp/App/MyClassA.php';
//use '/myApp/App/MyClassA.php';
require_once __DIR__.'/myApp/App/DButil.php';

// Create and configure Slim app
$config = ['settings' => [  'addContentLengthHeader' => false,
                            'displayErrorDetails'=> true,
                          ]
          ];

// SlimApp DB
$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "root";
$config['db']['dbname'] = "slim_1_one";

// Opencart DB
/*
$config['db']['host']   = "localhost";
$config['db']['user']   = "opencart";
$config['db']['pass']   = "opencart123";
$config['db']['dbname'] = "opencart_3.0.2.0";
*/

//
$app = new \Slim\App($config);

require_once __DIR__.'/myApp/App/app_routes.php';


// Run app
$app->run();


?>
