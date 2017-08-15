<?php 

//
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
//
require 'vendor/autoload.php';
//
require_once __DIR__.'/myApp/App/MyClassA.php';
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


// Define app routes
$app->get('/',function($request,$response,$args){
  
  /*
  $a = new MyClassA('saumya');
  $conn = $a->getConnection($this->db);
  var_dump($conn);
  */

  //$dbUtil = new myApp\App\DButil('saumya');
  /*
  $dbUtil = new DButil('saumya');
  $dbUtil->getConnection($this->db);
  */

  //var_dump(__DIR__.'/myApp/App/MyClassA.php');

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
    /*
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    // not necessary but useful
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    */
    
    $dbUtil = new DButil('saumya');
    $pdo = $dbUtil->getConnection($this->db);
    
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
    /*
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    // not necessary but useful
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    */
    
    $dbUtil = new DButil('saumya');
    $pdo = $dbUtil->getConnection($this->db);

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
  // =============================================================================
  // CREATE / Add
  // =============================================================================
  //$this->get('/add/customer/{fname}/{lname}','addNewCustomer');
  //$this->post('/add/customer','addNewCustomer');
  $this->post('/add/customer',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $userData = ($request->getParsedBody());

    //$sql = "INSERT INTO oc_customer (firstname,lastname) VALUES (:userFirstName,:userLastName)";
    $sql = "INSERT INTO app_customer (name,phone,address) VALUES (:uName,:uPhone,:uAddress)";
    $sth = $pdo->prepare($sql);
    $sth->bindParam("uName", $userData['customerName']);
    $sth->bindParam("uPhone", $userData['customerPhone']);
    $sth->bindParam("uAddress", $userData['customerAddress']);
    $sth->execute();
    
    $input['id'] = $pdo->lastInsertId();
    return $response->withJson($input);

    //return var_dump($userData);
  });

  $this->post('/add/company',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $userData = ($request->getParsedBody());

    $sql = "INSERT INTO app_product_company (name,note) VALUES (:companyName,:companyNote)";
    $sth = $pdo->prepare($sql);
    $sth->bindParam("companyName", $userData['cname']);
    $sth->bindParam("companyNote", $userData['cnote']);
    $sth->execute();
    
    $input['id'] = $pdo->lastInsertId();
    return $response->withJson($input);

    //return var_dump($userData);
  });

  $this->post('/add/product',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $userData = ($request->getParsedBody());

    $sql = "INSERT INTO app_product (name,id_company,price) VALUES (:productName,:companyId,:productPrice)";
    $sth = $pdo->prepare($sql);
    $sth->bindParam("productName", $userData['product_name']);
    $sth->bindParam("companyId", $userData['company_id']);
    $sth->bindParam("productPrice", $userData['product_price']);
    $sth->execute();
    
    $input['id'] = $pdo->lastInsertId();
    return $response->withJson($input);

    //return var_dump($userData);
  });

  //END - CREATE / Add
  // =============================================================================
  // READ / View
  // =============================================================================
  $this->get('/read/customer',function($request, $response, $args){
      $db = $this->db;
      $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      $userData = ($request->getParsedBody());

      $sql = "SELECT * FROM app_customer";
      $sth = $pdo->prepare($sql);
      $sth->execute();

      $allCustomers = $sth->fetchAll();

      $sth = null;
      $pdo = null;
      
      $newResponse = $response->withHeader('Content-type', 'application/json');
      $newResponse = $response->withStatus(200);
      $newResponse = $response->withJson($allCustomers);

      return $newResponse;
  });
  $this->get('/read/company',function($request, $response, $args){
      $db = $this->db;
      $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      $userData = ($request->getParsedBody());

      $sql = "SELECT * FROM app_product_company";
      $sth = $pdo->prepare($sql);
      $sth->execute();

      $allResults = $sth->fetchAll();

      $sth = null;
      $pdo = null;
      
      $newResponse = $response->withHeader('Content-type', 'application/json');
      $newResponse = $response->withStatus(200);
      $newResponse = $response->withJson($allResults);

      return $newResponse;
  });
  $this->get('/read/product',function($request, $response, $args){
      $db = $this->db;
      $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
      $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

      $userData = ($request->getParsedBody());

      $sql = "SELECT * FROM app_product";
      $sth = $pdo->prepare($sql);
      $sth->execute();

      $allResults = $sth->fetchAll();

      $sth = null;
      $pdo = null;
      
      $newResponse = $response->withHeader('Content-type', 'application/json');
      $newResponse = $response->withStatus(200);
      $newResponse = $response->withJson($allResults);

      return $newResponse;
  });
  //END - READ / View
  // =============================================================================
  // UPDATE / Edit
  // =============================================================================
  $this->post('/update/customer',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $userData = ($request->getParsedBody());

    $sql = "UPDATE `app_customer` 
            SET `name`= :uName, 
                `phone`= :uPhone, 
                `address`= :uAddress 
            WHERE `id`= :id";

    $sth = $pdo->prepare($sql);
    
    $sth->bindParam("uName", $userData['customerName']);
    $sth->bindParam("uPhone", $userData['customerPhone']);
    $sth->bindParam("uAddress", $userData['customerAddress']);
    $sth->bindParam("id", $userData['id']);
    
    $sth->execute();
    
    //$input['id'] = $pdo->lastInsertId();
    //return $response->withJson($input);
    return $sth->rowCount().' records UPDATED successfully.';
  });
  $this->post('/update/company',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $userData = ($request->getParsedBody());

    $sql = "UPDATE `app_product_company` 
            SET `name`= :dCompanyName, 
                `note`= :dCompanyNote 
            WHERE `id`= :did";

    $sth = $pdo->prepare($sql);
    
    $sth->bindParam("dCompanyName", $userData['companyName']);
    $sth->bindParam("dCompanyNote", $userData['companyNote']);
    $sth->bindParam("did", $userData['id']);
    
    $sth->execute();
    
    return $sth->rowCount().' records UPDATED successfully.';
  });
  $this->post('/update/product',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $userData = ($request->getParsedBody());

    $sql = "UPDATE `app_product` 
            SET `name`= :dProductName, 
                `id_company`= :didCompany,
                `price`= :dProductPrice 
            WHERE `id`= :did";

    $sth = $pdo->prepare($sql);
    
    $sth->bindParam("dProductName", $userData['productName']);
    $sth->bindParam("didCompany", $userData['idCompany']);
    $sth->bindParam("dProductPrice", $userData['productPrice']);
    $sth->bindParam("did", $userData['id']);
    
    $sth->execute();
    
    return $sth->rowCount().' records UPDATED successfully.';    
  });
  //END UPDATE / Edit
  // =============================================================================
  // DELETE / Edit
  // =============================================================================
  $this->post('/delete/customer',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $userData = ($request->getParsedBody());

    $sql = "DELETE FROM `app_customer` WHERE `id` = :did";
    
    $sth = $pdo->prepare($sql);
    $sth->bindParam("did", $userData['id']);
    $sth->execute();
    
    return $sth->rowCount().' records DELETED successfully.'; 
  });
  $this->post('/delete/company',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $userData = ($request->getParsedBody());

    $sql = "DELETE FROM `app_product_company` WHERE `id` = :did";
    
    $sth = $pdo->prepare($sql);
    $sth->bindParam("did", $userData['id']);
    $sth->execute();
    
    return $sth->rowCount().' records DELETED successfully.';
  });
  $this->post('/delete/product',function($request, $response, $args){
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

    $userData = ($request->getParsedBody());

    $sql = "DELETE FROM `app_product` WHERE `id` = :did";
    
    $sth = $pdo->prepare($sql);
    $sth->bindParam("did", $userData['id']);
    $sth->execute();
    
    return $sth->rowCount().' records DELETED successfully.';    
  });
  //END DELETE / Edit
  //
});


// Run app
$app->run();


?>
