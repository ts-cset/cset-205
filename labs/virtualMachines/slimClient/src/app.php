<?php
namespace feather\firstSlimClient;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
require './vendor/autoload.php';

class App
{

   private $app;
   public function __construct() {

     $app = new \Slim\App(['settings' => $config]);

     $container = $app->getContainer();

     $container['logger'] = function($c) {
         $logger = new \Monolog\Logger('my_logger');
         $file_handler = new \Monolog\Handler\StreamHandler('./logs/app.log');
         $logger->pushHandler($file_handler);
         return $logger;
     };
     function makeApiRequest($path){
       $ch = curl_init();

       //Set the URL that you want to GET by using the CURLOPT_URL option.
       curl_setopt($ch, CURLOPT_URL, "http://localhost/firstSlim/$path");
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

       $response = curl_exec($ch);
       return json_decode($response, true);
     }
     $app->get('/', function (Request $request, Response $response) {
       $responseRecords = makeApiRequest('people');
       $tableRows = "<tr><th>Name</th><th>Age</th><th>Occupation<th>Actions</th></th>";
       foreach($responseRecords as $person) {
         $tableRows = $tableRows . "<tr>";
         $tableRows = $tableRows . "<td>".$person["name"]."</td><td>".$person["age"]."</td><td>".$person["occupation"]."</td>";
         $tableRows = $tableRows . "</tr>";
       }
       $body = '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">';

       $body = $body . "<h1>People</h1>
       <table class='table'>
       $tableRows
       </table>";
       $response->write($body);
         return $response;
     });
     $this->app = $app;
   }

   /**
    * Get an instance of the application.
    *
    * @return \Slim\App
    */
   public function get()
   {
       return $this->app;
   }
 }
