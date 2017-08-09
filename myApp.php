<?php 

require 'vendor/autoload.php';

// Create and configure Slim app
$config = ['settings' => ['addContentLengthHeader' => false,]];

$config['displayErrorDetails'] = true;
//$config['addContentLengthHeader'] = false;

$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "root";
$config['db']['dbname'] = "slim_1_one";


//
$app = new \Slim\App($config);

// Define app routes
$app->get('/hello/{name}', function ($request, $response, $args) {
    return $response->write("Hello " . $args['name']);
});
// Optional arguements
$app->get('/testIt[/{name}]',function($request, $response, $args) {
  //$app->render( 'beta_register.php', array('page_title' => "Beta Register",'data'=>'' ) );
  /*
  $response = $app->response();
  //$response['Content-Type'] = 'application/json';
  $response->headers->set('Content-Type', 'application/json');
  $response->status(200);
  $dataObj='{"API": "1.0.0","by":"saumya"}';
  $response->body($dataObj);
  */

  
  //redirect
  //return $response->withStatus(302)->withHeader('Location', 'http://google.com');
  //
  echo "Hello World! <br /> Really! ".$args['name'].'?';
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