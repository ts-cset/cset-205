<?php
namespace feather\firstSlimClient;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\PhpRenderer;

require './vendor/autoload.php';

class App
{
   private $app;
   private const SCRIPT_INCLUDE = '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
   <script
     src="https://code.jquery.com/jquery-3.3.1.min.js"
     integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
     crossorigin="anonymous"></script>
   </head>
   <script src=".public/script.js"></script>';


   public function __construct() {

     $app = new \Slim\App(['settings' => $config]);

     $container = $app->getContainer();

     $container['logger'] = function($c) {
         $logger = new \Monolog\Logger('my_logger');
         $file_handler = new \Monolog\Handler\StreamHandler('./logs/app.log');
         $logger->pushHandler($file_handler);
         return $logger;
     };
     $container['renderer'] = new PhpRenderer("./templates");

     function makeApiRequest($path){
       $ch = curl_init();

       //Set the URL that you want to GET by using the CURLOPT_URL option.
       curl_setopt($ch, CURLOPT_URL, "http://localhost/firstSlim/$path");
       curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
       curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

       $response = curl_exec($ch);
       return json_decode($response, true);
     }
     $app->get('/', function (Request $request, Response $response, array $args) {
       $responseRecords = makeApiRequest('people');
       $tableRows = "";
       foreach($responseRecords as $person) {
         $tableRows = $tableRows . "<tr>";
         $tableRows = $tableRows . "<td>".$person["name"]."</td><td>".$person["age"]."</td><td>".$person["occupation"]."</td>";
         $tableRows = $tableRows . "<td>
         <a href='http://localhost:8080/slimClient/people/".$person["id"]."' class='btn btn-primary'>View Details</a>
         <a href='http://localhost:8080/slimClient/people/".$person["id"]."/edit' class='btn btn-secondary'>Edit</a>
         <a data-id='".$person["id"]."' class='btn btn-danger deletebtn'>Delete</a>

         </td>";
         $tableRows = $tableRows . "</tr>";
       }

       $templateVariables = [
           "title" => "People",
           "tableRows" => $tableRows
       ];
       return $this->renderer->render($response, "/people.html", $templateVariables);
     });
     $app->get('/people/add', function(Request $request, Response $response) {
       $templateVariables = [
         "type" => "new",
         "title" => "Create Person"
       ];
       return $this->renderer->render($response, "/peopleForm.html", $templateVariables);

     });

     $app->get('/people/{id}', function (Request $request, Response $response, array $args) {
         $id = $args['id'];
         $responseRecords = makeApiRequest('people/'.$id);
         $body = "<h1>Name: ".$responseRecords['name']."</h1>";
         $body = $body . "<h2>Occupation: ".$responseRecords['occuptation']."</h2>";
         $body = $body . "<h3>Age: ".$responseRecords['age']."</h3>";
         $response->getBody()->write($body);
         return $response;
     });
     $app->get('/people/{id}/edit', function (Request $request, Response $response, array $args) {
         $id = $args['id'];
         $responseRecord = makeApiRequest('people/'.$id);
         $templateVariables = [
           "type" => "edit",
           "title" => "Edit User",
           "person" => $responseRecord
         ];
         return $this->renderer->render($response, "/peopleEditForm.html", $templateVariables);

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
