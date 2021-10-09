<?php
declare(strict_types=1);

namespace SIMDE\Ginger\Test\Action;

use SIMDE\Ginger\Domain\User\Data\User;
use Slim\Psr7\Environment;
use SIMDE\Ginger\Test\TestCase;
use Slim\Psr7\Uri;
use Slim\Factory\ServerRequestCreatorFactory;


class MembershipReadTest extends TestCase
{
  protected $app;

  public function setUp():void
  {
    $this->app = $this->getAppInstance();
  }
     
  public function testUnknownLogin() {
    $request = $this->createRequest("GET", "/falsy/cotisations", "key=validAppKey");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 404);
    $this->assertSame($responseContent["error"]["message"], "User not found");
    $this->assertSame($responseContent["error"]["code"], 404);
    // $this->assertSame($responseContent["error"]["detail"]["message"], "User not found");
    // $this->assertSame($responseContent["error"]["detail"]["errcode"], 404);
  } 
  
  public function testKnownLoginWithoutMembership() {
    $request = $this->createRequest("GET", "/testlogin/cotisations", "key=validAppKey");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 200);
    $this->assertEmpty($responseContent);
  } 
  
  public function testKnownLoginWithMembership() {
    $this->markTestIncomplete('This test has not been implemented yet.');
    $request = $this->createRequest("GET", "/testlogincotiz/cotisations", "key=validAppKey");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 200);
    $this->assertCount(1, $responseContent);
    $this->assertCount(4, $responseContent[0]);
  } 
  
}
