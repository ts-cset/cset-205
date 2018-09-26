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


     $app->get('/airports', function (Request $request, Response $response, array $args) {

         return $response;
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
