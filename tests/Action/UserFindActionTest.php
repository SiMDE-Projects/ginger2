<?php
declare(strict_types=1);

namespace SIMDE\Ginger\Test\Action\UserReadActionTest;

use SIMDE\Ginger\Domain\User\Data\User;
use Slim\Psr7\Environment;
use SIMDE\Ginger\Test\TestCase;
use Slim\Psr7\Uri;
use Slim\Factory\ServerRequestCreatorFactory;


class UserFindActionTest extends TestCase
{
  protected $app;

  public function setUp():void
  {
    $this->app = $this->getAppInstance();
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
}