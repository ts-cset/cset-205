<?php
use PHPUnit\Framework\TestCase;
use Slim\Http\Environment;
use Slim\Http\Request;
require './vendor/autoload.php';

// empty class definitions for phpunit to mock.
class mockQuery {
  public function fetchAll(){}
};
class mockDb {
  public function query(){}
}

class PeopleTest extends TestCase
{
    protected $app;
    protected $db;

    // execute setup code before each test is run
    public function setUp()
    {
      $this->db = $this->createMock('mockDb');
      $this->app = (new feather\firstSlim\App($this->db))->get();
    }

    // test the helloName endpoint
    public function testHelloName() {
      $env = Environment::mock([
          'REQUEST_METHOD' => 'GET',
          'REQUEST_URI'    => '/hello/Joe',
          ]);
      $req = Request::createFromEnvironment($env);
      $this->app->getContainer()['request'] = $req;
      $response = $this->app->run(true);
      $this->assertSame($response->getStatusCode(), 200);
      $this->assertSame((string)$response->getBody(), "Hello, Joe");
    }
}
