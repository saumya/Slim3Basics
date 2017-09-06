<?php

/**
*
* This is part of the `myApp.php` file
* Its here, just for organisation of code.
*
*/

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
    /*
    //$db = $this->get('db');
    $db = $this->db;
    $pdo = new PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    // not necessary but useful
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    // log
    //var_dump($pdo);
    */

    $dbUtil = new DButil('saumya');
    $pdo = $dbUtil->getConnection($this->db);

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

  $this->post('/add/product_bought',function($request, $response, $args){
    //
    $dbUtil = new DButil('saumya');
    $pdo = $dbUtil->getConnection($this->db);
    //
    $userData = ($request->getParsedBody());
    //
    //$sql = "INSERT INTO app_product_bought (table_column_name) VALUES (:userValue)";
    $sql = "INSERT INTO app_product_bought (product_id,quantity,b_date) VALUES (:boughtId,:boughtQuantity,:boughtDate)";
    $sth = $pdo->prepare($sql);
    //
    $sth->bindParam("boughtId", $userData['bought_id']);
    $sth->bindParam("boughtQuantity", $userData['bought_quantity']);
    $sth->bindParam("boughtDate", $userData['bought_date']); // bought_date format - 2017-12-13, YYYY-MM-DD
    $sth->execute();
    //
    $input['id'] = $pdo->lastInsertId();
    //
    $sth = null; $pdo = null;
    //
    $newResponse = $response->withHeader('Content-type', 'application/json');
    $newResponse = $response->withStatus(200);
    $newResponse = $response->withJson($input);
    //
    return $newResponse;
  });
  $this->post('/add/product_sold',function($request, $response, $args){
    //
    $dbUtil = new DButil('saumya');
    $pdo = $dbUtil->getConnection($this->db);
    //
    $userData = ($request->getParsedBody());
    //
    //$sql = "INSERT INTO app_product_bought (table_column_name) VALUES (:userValue)";
    $sql = "INSERT INTO app_product_sold (product_id,customer_id,quantity,s_date) VALUES (:soldId,:soldToPersonId,:soldQuantity,:soldDate)";
    $sth = $pdo->prepare($sql);
    //
    $sth->bindParam("soldId", $userData['sold_id']);
    $sth->bindParam("soldQuantity", $userData['sold_quantity']);
    $sth->bindParam("soldToPersonId", $userData['sold_to_person_id']);
    $sth->bindParam("soldDate", $userData['sold_date']); // sold_date format - 2017-12-13, YYYY-MM-DD
    $sth->execute();
    //
    $input['id'] = $pdo->lastInsertId();
    //
    $sth = null; $pdo = null;
    //
    $newResponse = $response->withJson($input);
    return $newResponse;
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
  // DELETE 
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

    //=============
    //$allResults = $sth->fetchAll();
    $allResults = array("NumRecordsDeleted"=>$sth->rowCount()) ;

    $sth = null;
    $pdo = null;
    
    $newResponse = $response->withHeader('Content-type', 'application/json');
    $newResponse = $response->withStatus(200);
    $newResponse = $response->withJson($allResults);
    //=============
    
    //return $sth->rowCount().' records DELETED successfully.'; 
    return $newResponse;
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
    
    //return $sth->rowCount().' records DELETED successfully.';
    $allResults = array("NumRecordsDeleted"=>$sth->rowCount()) ;

    $sth = null;
    $pdo = null;
    
    $newResponse = $response->withHeader('Content-type', 'application/json');
    $newResponse = $response->withStatus(200);
    $newResponse = $response->withJson($allResults);

    return $newResponse;
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
    
    //return $sth->rowCount().' records DELETED successfully.'; 
    $allResults = array("NumRecordsDeleted"=>$sth->rowCount()) ;

    $sth = null;
    $pdo = null;
    
    $newResponse = $response->withHeader('Content-type', 'application/json');
    $newResponse = $response->withStatus(200);
    $newResponse = $response->withJson($allResults);
    //=============
    
    //return $sth->rowCount().' records DELETED successfully.'; 
    return $newResponse;   
  });
  // =============================================================================
  // END DELETE
  // =============================================================================
  // ================================================
  // Prescription | app_visit is TableName | START
  // ================================================
  // CREATE
  // ===========
  $this->post('/add/prescription',function($request, $response, $args){
    //
    $dbUtil = new DButil('saumya');
    $pdo = $dbUtil->getConnection($this->db);
    //
    $userData = ($request->getParsedBody());
    //
    //$sql = "INSERT INTO app_product_bought (table_column_name) VALUES (:userValue)";
    $sql = "INSERT INTO app_visit (id_customer,p_date,symptom,prescription,note) VALUES (:customerId,:prescriptionDate,:symptom,:prescription,:note)";
    $sth = $pdo->prepare($sql);
    //
    $sth->bindParam("customerId", $userData['customer_id']);
    $sth->bindParam("prescriptionDate", $userData['prescription_date']); // prescription_date format - 2017-12-13, YYYY-MM-DD
    $sth->bindParam("symptom", $userData['customer_symptom']);
    $sth->bindParam("prescription", $userData['doctor_prescription']);
    $sth->bindParam("note", $userData['customer_note']);
    $sth->execute();
    //
    $input['id'] = $pdo->lastInsertId();
    //
    $sth = null; $pdo = null;
    //
    $newResponse = $response->withJson($input);
    return $newResponse;
  });
  // ===========
  // UPDATE
  // ===========
  $this->post('/update/prescription',function($request, $response, $args){

    $dbUtil = new DButil('saumya');
    $pdo = $dbUtil->getConnection($this->db);

    $userData = ($request->getParsedBody());

    $sql = "UPDATE `app_visit` 
            SET `id_customer`= :customerId, 
                `p_date`= :prescriptionDate,
                `symptom`= :d_symptom, 
                `prescription`= :d_prescription,  
                `note`= :d_note 
            WHERE `id`= :did";

    $sth = $pdo->prepare($sql);
    
    $sth->bindParam("customerId", $userData['customer_id']);
    $sth->bindParam("prescriptionDate", $userData['prescription_date']); // prescription_date format - 2017-12-13, YYYY-MM-DD
    $sth->bindParam("d_symptom", $userData['customer_symptom']);
    $sth->bindParam("d_prescription", $userData['doctor_prescription']);
    $sth->bindParam("d_note", $userData['customer_note']);
    $sth->bindParam("did", $userData['id']);
    
    $sth->execute();
    $updateCount = $sth->rowCount();

    $sth = null; $pdo = null;
    
    return $updateCount.' records UPDATED successfully.';    
  });
  // ===========
  // DELETE
  // ===========
  $this->post('/delete/prescription',function($request, $response, $args){
    $dbUtil = new DButil('saumya');
    $pdo = $dbUtil->getConnection($this->db);

    $userData = ($request->getParsedBody());

    $sql = "DELETE FROM `app_visit` WHERE `id` = :did";
    
    $sth = $pdo->prepare($sql);
    $sth->bindParam("did", $userData['id']);
    $sth->execute();
    $updateCount = $sth->rowCount();

    $sth = null; $pdo = null;
    
    return $updateCount.' records DELETED successfully.';    
  });
  // ===========
  // READ
  // ===========
  $this->get('/allPrescriptions[/{customer_id}]',function($request, $response, $args){
    $dbUtil = new DButil('saumya');
    $pdo = $dbUtil->getConnection($this->db);

    //$userData = ($request->getParsedBody());
    //var_dump($userData);

    //var_dump($args);
    //var_dump($args['customer_id']);

    $sql = "SELECT * FROM `app_visit`";
    if ($args['customer_id'] == NULL) {
      // Do Nothing
    }else{
      $cid = $args['customer_id'];
      $sql = "SELECT * FROM `app_visit` WHERE `id_customer` = $cid";
    }
    
    $sth = $pdo->prepare($sql);
    //$sth->bindParam("did", $userData['id']);
    $sth->execute();
    $allResults = $sth->fetchAll();

    $sth = null; $pdo = null;

    $newResponse = $response->withHeader('Content-type', 'application/json');
    $newResponse = $response->withStatus(200);
    $newResponse = $response->withJson($allResults);
    
    return $newResponse;    
  });
  // ================================================
  // Prescription | app_visit is TableName | END 
  // ================================================
  // ================================================
  // Report | TableName: app_product_sold  | START >>>
  // ================================================
  $this->get('/allSold[/{customer_id}]',function($request, $response, $args){
    $dbUtil = new DButil('saumya');
    
    $pdo = $dbUtil->getConnection($this->db);
    $sql = "SELECT * FROM `app_product_sold`";

    if ($args['customer_id'] == NULL) {
      // Do Nothing
    }else{
      $cid = $args['customer_id'];
      $sql = "SELECT * FROM `app_product_sold` WHERE `customer_id` = $cid";
    }
    
    $sth = $pdo->prepare($sql);
    $sth->execute();
    $allResults = $sth->fetchAll();
    
    $sth = null; $pdo = null;

    $newResponse = $response->withHeader('Content-type', 'application/json');
    $newResponse = $response->withStatus(200);
    $newResponse = $response->withJson($allResults);
    
    return $newResponse;    
  });
  $this->get('/allBought[/{customer_id}]',function($request, $response, $args){
    $dbUtil = new DButil('saumya');
    
    $pdo = $dbUtil->getConnection($this->db);
    $sql = "SELECT * FROM `app_product_bought`";

    if ($args['customer_id'] == NULL) {
      // Do Nothing
    }else{
      $cid = $args['customer_id'];
      $sql = "SELECT * FROM `app_product_bought` WHERE `customer_id` = $cid";
    }
    
    $sth = $pdo->prepare($sql);
    $sth->execute();
    $allResults = $sth->fetchAll();
    
    $sth = null; $pdo = null;

    $newResponse = $response->withHeader('Content-type', 'application/json');
    $newResponse = $response->withStatus(200);
    $newResponse = $response->withJson($allResults);
    
    return $newResponse;    
  });  
  // ================================================
  // Report | TableName: app_product_sold  | END <<<<<
  // ================================================
  
});

?>