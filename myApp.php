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

// Define app routes
$app->get('/',function($request,$response,$args){
  echo '<div style="font-size:1.0em;text-align:center;background-color:yellow;max-width:200px;min-height:2em;max-height:2em;margin:auto;padding-top:1em;"> Slim version - '.\Slim\App::VERSION.'</div> <br />';
  echo "<div style='font-size:2em;'> Sample API </div> <br />";
  echo '<a href="myApp.php/hello">Hello Test: Mandatory Params</a> <br />';
  echo '<a href="myApp.php/testJSON">Just Test: Optional Params</a> <br />';
  echo "<br/>";
  echo "<div style='font-size:2em;'> Status API </div> <br />";
  echo '<a href="myApp.php/status/version">Version</a> <br />';
  echo '<a href="myApp.php/status/app">App Status</a> <br />';
  echo '<a href="myApp.php/status/api">API Status</a> <br />';
  echo '<a href="myApp.php/status/db">DB Status</a> <br />';
  echo "<br/>";
  echo "<div style='font-size:4em;'> Product API </div> <br />";
  echo '<a href="myApp.php/v1.0.0/countries">All Countries</a> <br />';
  echo '<a href="myApp.php/v1.0.0/customers">All Customers</a> <br />';
  echo '<a href="myApp.php/v1.0.0/add/customer">Add Customer</a> <br />';
});

// Mandatory Arguements
$app->get('/hello/{name}', function (Request $request,Response $response, $args) {
    return $response->write("Hello " . $args['name']);
});
// Optional Arguements
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

// Status
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

// Product
$app->group('/v1.0.0', function(){
  $this->get('/countries',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    // not necessary but useful
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $sth = $pdo->prepare("SELECT * FROM oc_country");
    $sth->execute();
    $allCountries = $sth->fetchAll();
    $sth = null;
    $pdo = null;
    // rturning a JSON response
    //$dataObj = json_encode($allCountries);
    $newResponse = $response->withHeader('Content-type', 'application/json');
    $newResponse = $response->withStatus(200);
    //$body = $response->getBody();
    //$body->write($dataObj);
    //return $newResponse;

    // Slim has a method for JSON response withJson()
    $newResponse = $response->withJson($allCountries);
    return $newResponse;
  });
  $this->get('/customers',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    // not necessary but useful
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $sth = $pdo->prepare("SELECT * FROM oc_customer");
    $sth->execute();
    $allCountries = $sth->fetchAll();
    $sth = null;
    $pdo = null;
    // returning a JSON response
    //$dataObj = json_encode($allCountries);
    $newResponse = $response->withHeader('Content-type', 'application/json');
    $newResponse = $response->withStatus(200);
    //$body = $response->getBody();
    //$body->write($dataObj);

    // Slim has a method for JSON response withJson()
    $newResponse = $response->withJson($allCountries);
    return $newResponse;
  });


  //$this->get('/add/customer/{fname}/{lname}','addNewCustomer');
  //$this->post('/add/customer','addNewCustomer');
  $this->post('/add/customer',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $userData = ($request->getParsedBody());

    $sql = "INSERT INTO oc_customer (firstname,lastname) VALUES (:userFirstName,:userLastName)";
    $sth = $pdo->prepare($sql);
    $sth->bindParam("userFirstName", $userData['fname']);
    $sth->bindParam("userLastName", $userData['lname']);
    $sth->execute();
    
    $input['id'] = $pdo->lastInsertId();
    return $response->withJson($input);

    //return var_dump($userData);
  });
  //
});


// Run app
$app->run();

//

function addNewCustomer ($request, $response, $arguements){
  //$db = $this->db;
  $dbSettings = $app->db;
  $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
  // not necessary but useful
  //$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  //$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  //===========

  $userData = ($request->getParsedBody());
  //$fname = $userData['fname'];
  //$lname = $userData['lname'];

  //return $userData['fname'];
  /*
  $sql = "INSERT INTO oc_customer (firstname) VALUES (:userFirstName)";
  $sth = $app->db->prepare($sql);
  $sth->bindParam("userFirstName", $userData['fname']);
  $sth->execute();
  */
  //$input['id'] = $this->db->lastInsertId();
  //return $response->withJson($userData);
  return true;


  //var_dump($arguements);
  /*
  $userData = json_decode($request->getBody());
  
  //
  $db = connect_db();
  //
  $request = $app->request;
  $userData = json_decode($request->getBody());

  // DB
  try{ 
      $sql = "INSERT INTO oc_customer (name, phone, email, address, pincode) VALUES (?, ?, ?, ?, ?)";
      if($stmt = $pdo->prepare($sql)){
          $stmt->bind_param("sissi",$name,$phone,$email,$address,$pin);
          //
          $name = $userData->uName;
          $phone = $userData->phone;
          $email = $userData->email;
          $address = $userData->address;
          $pin = $userData->pin;
          //
          //echo json_encode($stmt);
          $stmt->execute();
          //
          $msg = 'SUCCESS';
          echo json_encode('{"result":"SUCCESS","message":"'.$msg.'"}');
          //echo ("{'result':'SUCCESS','message':'$msg'}");
          //
          $stmt->close();
          $db->close();
      }else{
          $msg = 'FAIL : $db->prepare($sql) : '.json_encode($stmt);
          //echo 'FAIL : $db->prepare($sql) : '.json_encode($stmt);
          echo json_encode("{'result':'FAIL','message':'$msg'}");
      }
  }catch(Exception $e){
      $msg = 'FAIL : $db->prepare($sql) : '.json_encode(var_dump($e));
      //echo '{"error":{"text":'. $e->getMessage() .'}}';
      echo json_encode("{'result':'FAIL','message':'$msg'}");
  }
  */
  //===========
  //return echo "Hello";
  //echo "string";
  //return $response->write("App is ready to work on API.");
}

?>
