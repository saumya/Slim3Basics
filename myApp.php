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
/*
$config['db']['host']   = "localhost";
$config['db']['user']   = "root";
$config['db']['pass']   = "root";
$config['db']['dbname'] = "slim_1_one";
*/
// Opencart DB
$config['db']['host']   = "localhost";
$config['db']['user']   = "opencart";
$config['db']['pass']   = "opencart123";
$config['db']['dbname'] = "opencart_3.0.2.0";


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

$app->get('/',function($request,$response,$args){
  echo '<a href="myApp.php/hello">Hello Test: Mandatory Params</a> <br />';
  echo '<a href="myApp.php/testJSON">Just Test: Optional Params</a> <br />';
  echo "<br/>";
  echo '<a href="myApp.php/status/version">Version</a> <br />';
  echo '<a href="myApp.php/status/app">App Status</a> <br />';
  echo '<a href="myApp.php/status/api">API Status</a> <br />';
  echo '<a href="myApp.php/status/db">DB Status</a> <br />';
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
  $this->get('/db',function($request, $response, $args){
    //$settings = $this->get('settings')['displayErrorDetails'];
    //$db = $this->get('db');
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    // not necessary but useful
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // log
    //var_dump($pdo);

    // use the connection here
    //$sth = $pdo->query('SELECT * FROM oc_category');
    $sth = $pdo->prepare("SELECT * FROM oc_category");
    $sth->execute();
    //var_dump($sth);
    $allCategories = $sth->fetchAll();
    //var_dump($allCategories);
    // and now we're done; close it
    $sth = null;
    $pdo = null;

    //var_dump($allCategories);
    //$dataObj='{"API": "1.0.0","by":"'.$args['name'].'"}';
    /*
    echo "All Categories <br/>";
    foreach ($allCategories as $category) {
      echo 'category_id='.$category['category_id'].': parent_id='.$category['parent_id'] . '<br />';
    }
    */
    //var_dump(json_encode($allCategories));

    //return $response->write('API is getting ready.');
    //return $pdo;
    //return(json_encode($allCategories));

    // rturning a JSON response
    $dataObj = json_encode($allCategories);
    $newResponse = $response->withHeader('Content-type', 'application/json');
    $newResponse = $response->withStatus(200);
    $body = $response->getBody();
    $body->write($dataObj);
    return $newResponse;
  });
});


// Run app
$app->run();


?>
