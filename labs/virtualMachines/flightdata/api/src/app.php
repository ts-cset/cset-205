<?php
namespace feather\firstSlim;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Tuupola\Middleware\HttpBasicAuthentication as basicAuth;
require './vendor/autoload.php';

class App
{

   private $app;
   public function __construct($db) {
     $config = [];
     $config['db']['host']   = 'localhost';
     $config['db']['user']   = 'root';
     $config['db']['pass']   = 'root';
     $config['db']['dbname'] = 'flightdata';

     $app = new \Slim\App(['settings' => $config]);

     $container = $app->getContainer();
     $container['db'] = $db;

     $container['logger'] = function($c) {
         $logger = new \Monolog\Logger('my_logger');
         $file_handler = new \Monolog\Handler\StreamHandler('./logs/app.log');
         $logger->pushHandler($file_handler);
         return $logger;
     };

     function destinations($airports, $flights) {
       $numFlights = count($flights);
       $percentages = [];
       foreach ($airports as $airport) {
         $airport_code = $airport['airport'];
         $matching_flights = array_filter($flights,  function($flight) use ($airport_code){
              return ($flight['dest'] == $airport_code);
          });
          $percentages[] = ['value'=> (count($matching_flights) / $numFlights * 100), 'label'=> $airport_code];
       }
       return $percentages;
     }

     $app->get('/airports', function (Request $request, Response $response, array $args) {
       $airports = $this->db->query('SELECT * from airports')->fetchAll();
       $flights = $this->db->query('SELECT * from flights')->fetchAll();
       $destinations = destinations($airports, $flights);

       $jsonResponse = $response->withJson($destinations);
       return $jsonResponse;
     });
     $app->get('/flights', function (Request $request, Response $response) {

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
