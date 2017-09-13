Slim 3, Basics.
===============

An Application using [Slim 3][1] framework using [Composer][3]. Here is the [official docs][2] as, how to install.

 - First of all, install `Composer` locally in a folder.
 - Now put the dependencies inside `composer.json` file.
 - Run the command to install the dependencies.

```
/Applications/MAMP/bin/php/php7.1.0/bin/php composer.phar install
```
Start using the framework.

My [learning of Slim started with Slim 1][4] and never really done anything on Slim 2.


Available APIs

```
http://localhost:8888/slim/app_1/myApp.php/
http://localhost:8888/slim/app_1/myApp.php/hello/{name}
http://localhost:8888/slim/app_1/myApp.php/testJSON[/{name}]

http://localhost:8888/slim/app_1/myApp.php/status
http://localhost:8888/slim/app_1/myApp.php/status/version
http://localhost:8888/slim/app_1/myApp.php/status/app
http://localhost:8888/slim/app_1/myApp.php/status/api
http://localhost:8888/slim/app_1/myApp.php/status/db

http://localhost:8888/slim/app_1/myApp.php/v1.0.0/countries
http://localhost:8888/slim/app_1/myApp.php/v1.0.0/customers
http://localhost:8888/slim/app_1/myApp.php/v1.0.0/customers
```

The CRUD API

```
[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/add/customer
[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/add/company
[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/add/product

[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/add/product_bought
[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/add/product_sold

[GET]  http://localhost:8888/slim/app_1/myApp.php/v1.0.0/read/customer
[GET]  http://localhost:8888/slim/app_1/myApp.php/v1.0.0/read/company
[GET]  http://localhost:8888/slim/app_1/myApp.php/v1.0.0/read/product

[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/update/customer
[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/update/company
[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/update/product

[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/delete/customer
[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/delete/company
[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/delete/product

[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/add/prescription
[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/update/prescription
[POST] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/delete/prescription

[GET]  http://localhost:8888/slim/app_1/myApp.php/v1.0.0/allPrescriptions 
[GET]  http://localhost:8888/slim/app_1/myApp.php/v1.0.0/allPrescriptions/2 

```

Reports API

```
[GET] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/boughtByDate/2017-09-06
[GET] http://localhost:8888/slim/app_1/myApp.php/v1.0.0/soldByDate/2017-09-05
```

Todo: 
 - API for report
 - Analytics API
 - Research for server side rendering 




 [1]: https://www.slimframework.com/
 [2]: https://www.slimframework.com/docs/start/installation.html
 [3]: https://getcomposer.org
 [4]: https://github.com/saumya/slimCraft