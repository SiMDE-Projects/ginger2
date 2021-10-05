<?php
declare(strict_types=1);

namespace SIMDE\Ginger\Test\Action\UserReadActionTest;

use SIMDE\Ginger\Domain\User\Data\User;
use Slim\Psr7\Environment;
use SIMDE\Ginger\Test\TestCase;
use Slim\Psr7\Uri;
use Slim\Factory\ServerRequestCreatorFactory;


class UserReadActionTest extends TestCase
{
  protected $app;

  public function setUp():void
  {
    $this->app = $this->getAppInstance();
  }
     
  public function testUnknownKey() {
    $request = $this->createRequest("GET", "/falsy", "key=unknownkey");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 500);
    $this->assertSame($responseContent["error"]["message"], "Uncaught exception");
    $this->assertSame($responseContent["error"]["code"], 500);
    $this->assertSame($responseContent["error"]["detail"]["message"], "Application not found by key in db");
    $this->assertSame($responseContent["error"]["detail"]["errcode"], 404);
  } 
  
  public function testDisabledKey() {
    $request = $this->createRequest("GET", "/falsy", "key=removedAppKey");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 500);
    $this->assertSame($responseContent["error"]["message"], "Uncaught exception");
    $this->assertSame($responseContent["error"]["code"], 500);
    $this->assertSame($responseContent["error"]["detail"]["message"], "Application not found by key in db");
    $this->assertSame($responseContent["error"]["detail"]["errcode"], 404);
  } 
  
  public function testNoKey() {
    $request = $this->createRequest("GET", "/falsy");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 401);
    $this->assertSame($responseContent["error"]["message"], "Unauthorized");
    $this->assertSame($responseContent["error"]["code"], 401);
    $this->assertSame($responseContent["error"]["detail"]["message"], "Api key is missing");
    $this->assertSame($responseContent["error"]["detail"]["errcode"], 401);
  }
  
  public function testLoginNotFound() {
    $request = $this->createRequest("GET", "/falsy", "key=validAppKey");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 404);
    $this->assertSame($responseContent["error"]["message"], "User not found");
    $this->assertSame($responseContent["error"]["code"], 404);
    $this->assertSame($responseContent["error"]["detail"]["message"], "User not found");
    $this->assertSame($responseContent["error"]["detail"]["errcode"], 404);
  }
  
  public function testCardNotFound() {
    $request = $this->createRequest("GET", "/badge/invalidCard", "key=validAppKey");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 404);
    $this->assertSame($responseContent["error"]["message"], "User not found");
    $this->assertSame($responseContent["error"]["code"], 404);
    $this->assertSame($responseContent["error"]["detail"]["message"], "User not found");
    $this->assertSame($responseContent["error"]["detail"]["errcode"], 404);
  }
  
  public function testCardFound() {
    $request = $this->createRequest("GET", "/badge/AABBCCDDEEFF", "key=validAppKey");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 200);
    $this->assertSame($responseContent["login"], "testlogin");
    $this->assertSame($responseContent["badge_uid"], "AABBCCDDEEFF");
  }
  
  public function testLoginFound() {
    $request = $this->createRequest("GET", "/badge/AABBCCDDEEFF", "key=validAppKey");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 200);
    $this->assertSame($responseContent["login"], "testlogin");
    $this->assertSame($responseContent["badge_uid"], "AABBCCDDEEFF");
  }
  
  public function testPartialFound() {
    $request = $this->createRequest("GET", "/find/john.doe", "key=validAppKey");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame(count($responseContent), 1);
    $this->assertSame(count($responseContent[0]), 4);
    $this->assertSame($responseContent[0]["login"], "testlogin");
    $this->assertSame($responseContent[0]["mail"], "john.doe@etu.utc.fr");
    $this->assertSame($responseContent[0]["nom"], "DOE");
    $this->assertSame($responseContent[0]["prenom"], "John");
    $this->assertSame($responseContent[0]["badge_uid"], null);
  }
  
  public function testMailFound() {
    $request = $this->createRequest("GET", "/mail/john.doe@etu.utc.fr", "key=validAppKey");
    $response = $this->app->handle($request);
    $responseContent = json_decode((string)$response->getBody(), true);
    $this->assertSame($response->getStatusCode(), 200);
    $this->assertSame($responseContent["login"], "testlogin");
    $this->assertSame($responseContent["mail"], "john.doe@etu.utc.fr");
    $this->assertSame($responseContent["nom"], "DOE");
    $this->assertSame($responseContent["prenom"], "John");
    $this->assertSame($responseContent["badge_uid"], "AABBCCDDEEFF");
  }
}