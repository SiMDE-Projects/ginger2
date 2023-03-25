<?php
declare(strict_types=1);

namespace SIMDE\Ginger\Test\Action;

use PDO;
use SIMDE\Ginger\Test\TestCase;
use Slim\App;


class MembershipCreateTest extends TestCase
{
    protected App $app;
    protected PDO $db;

    public function testUnknownLogin(): void
    {
        $responseContent = $this->callGinger("POST", "/falsy/cotisations", "key=validAppKey", null, 404);
        $this->assertSame($responseContent["error"]["message"], "User not found");
        $this->assertSame($responseContent["error"]["code"], 404);
    }

    public function testKnownLogin(): void
    {
        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertFalse($responseContent["is_cotisant"]);

        $body = [
            "debut" => "2003-11-19 00:00:00",
            "fin" => "2050-12-31 00:00:00",
            "montant" => 20,
        ];
        $responseContent = $this->callGinger("POST", "/testlogin/cotisations", "key=validAppKey", $body, 201);
        $this->assertCount(1, $responseContent);
        $this->assertArrayHasKey("result", $responseContent);

        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertTrue($responseContent["is_cotisant"]);
    }

    public function testInvalidStartDate(): void
    {
        $body = [
            "debut" => "2021-13-31 00:00:00",
            "fin" => "2050-12-31 00:00:00",
            "montant" => 20,
        ];
        $responseContent = $this->callGinger("POST", "/testlogin/cotisations", "key=validAppKey", $body, 400);
        $this->assertSame("Begin date is invalid", $responseContent["error"]["message"]);
    }

    public function testInvalidEndDate(): void
    {
        $body = [
            "debut" => "2021-11-30 00:00:00",
            "fin" => "2050-13-31 00:00:00",
            "montant" => 20,
        ];
        $responseContent = $this->callGinger("POST", "/testlogin/cotisations", "key=validAppKey", $body, 400);
        $this->assertSame("End date is invalid", $responseContent["error"]["message"]);
    }

    public function testEndDateBeforeStartDate(): void
    {
        $body = [
            "fin" => "2021-11-30 00:00:00",
            "debut" => "2050-11-30 00:00:00",
            "montant" => 20,
        ];
        $responseContent = $this->callGinger("POST", "/testlogin/cotisations", "key=validAppKey", $body, 400);
        $this->assertSame("Begin date must be inferior than end date", $responseContent["error"]["message"]);
    }

    public function testInvalidMontant(): void
    {
        $body = [
            "debut" => "2021-11-30 00:00:00",
            "fin" => "2050-11-30 00:00:00"
        ];
        $responseContent = $this->callGinger("POST", "/testlogin/cotisations", "key=validAppKey", $body, 400);
        $this->assertSame("Montant is invalid", $responseContent["error"]["message"]);
    }

    public function testOverlappingExistingMembership(): void
    {
        $this->db->exec("INSERT INTO `memberships` (`user_id`, `debut`, `fin`, `montant`, `created_at`, `deleted_at`)
      SELECT `id`, '2021-11-30', '2050-12-31', '20', NOW(), NULL
      FROM `users`
      WHERE `login`='testlogin';
    ");

        $body = [
            "debut" => "2021-12-01 00:00:00",
            "fin" => "2049-11-30 00:00:00",
            "montant" => 20,
        ];
        $responseContent = $this->callGinger("POST", "/testlogin/cotisations", "key=validAppKey", $body, 400);
        $this->assertSame("Membership date is overlapping an existing membership", $responseContent["error"]["message"]);
    }

    protected function setUp(): void
    {
        $this->app = $this->getAppInstance();
        $this->db = $this->container->get(PDO::class);
        $this->db->exec("DELETE `m` FROM `memberships` `m` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` = 'testlogin'");
        $this->db->exec("DELETE `c` FROM `cards` `c` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` = 'testlogin'");
        $this->db->exec("DELETE FROM `users` WHERE `login` = 'testlogin'");
        $this->db->exec("INSERT INTO `users` (`login`, `prenom`, `nom`, `mail`, `type`, `is_adulte`, `created_at`, `last_access`)
      VALUES ('testlogin', 'John', 'DOE', 'john.doe@etu.utc.fr', '0', '1', NOW(), NOW())
    ");
    }

    protected function tearDown(): void
    {
        $this->db->exec("DELETE m FROM memberships m inner join users u on (u.id = user_id) WHERE login = 'testlogin'");
        $this->db->exec("DELETE `c` FROM `cards` `c` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` = 'testlogin'");
        $this->db->exec("DELETE FROM `users` WHERE `login` = 'testlogin'");
    }

}
