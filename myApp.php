<?php 

//
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
//
require 'vendor/autoload.php';

// Create and configure Slim app
$config = ['settings' => [  'addContentLengthHeader' => false,
                            'displayErrorDetails'=> true,
                          ]
          ];

//$config['displayErrorDetails'] = true;
//$config['addContentLengthHeader'] = false;

$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "root";
$config['db']['dbname'] = "slim_1_one";


//
$app = new \Slim\App($config);

// Define app routes
$app->get('/hello/{name}', function (Request $request,Response $response, $args) {
    return $response->write("Hello " . $args['name']);
});
// Optional arguements
$app->get('/testJSON[/{name}]',function($request, $response, $args) {
  //1. make the data
  $dataObj='{"API": "1.0.0","by":"'.$args['name'].'"}';
  //$dataObj='{ "API" : "1.0.0" , "by" : "saumya" }';
  //2. make the new object
  $newResponse = $response->withHeader('Content-type', 'application/json');
  $newResponse = $response->withStatus(200);
  $body = $response->getBody();
  //$body->write(json_encode($dataObj));
  $body->write($dataObj);
  //3. return the new response
  return $newResponse;
  
  //redirect
  //return $response->withStatus(302)->withHeader('Location', 'http://google.com');
  //
  //echo "Hello World! <br /> Really! ".$args['name'].'?';
});

$app->group('/status', function(){
  $this->get('/version',function($request, $response, $args){
    return $response->write('0.0.1');
  });
  $this->get('/app',function($request, $response, $args){
    return $response->write("App is ready to work on API.");
  });
  $this->get('/api',function($request, $response, $args){
    return $response->write('API is getting ready.');
  });
});


// Run app
$app->run();


?>