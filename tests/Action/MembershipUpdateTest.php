<?php
declare(strict_types=1);

namespace SIMDE\Ginger\Test\Action;

use PDO;
use SIMDE\Ginger\Test\TestCase;
use Slim\App;


class MembershipUpdateTest extends TestCase
{
    protected App $app;
    protected PDO $db;

    public function testUnknownLogin(): void
    {
        $responseContent = $this->callGinger("PUT", "/falsy/cotisations/1", "key=validAppKey", [], 404);
        $this->assertSame($responseContent["error"]["message"], "User not found");
        $this->assertSame($responseContent["error"]["code"], 404);
    }

    public function testKnownLogin(): void
    {
        $body = $this->callGinger("GET", "/testlogin/cotisations", "key=validAppKey", [""])[0];

        $body["montant"] = 22;
        $body["debut"] = "2003-11-19";
        $body["fin"] = "2050-12-31";


        $responseContent = $this->callGinger("PUT", "/testlogin/cotisations/" . $body["id"], "key=validAppKey", $body, 201);
        $this->assertCount(1, $responseContent);
        $this->assertArrayHasKey("result", $responseContent);

        $responseContent = $this->callGinger("GET", "/testlogin/cotisations", "key=validAppKey");
        $this->assertCount(1, $responseContent);
        $this->assertSame($body["montant"], $responseContent[0]["montant"]);
        $this->assertSame($body["debut"], $responseContent[0]["debut"]);
        $this->assertSame($body["fin"], $responseContent[0]["fin"]);
    }

    public function testWrongLogin(): void
    {
        $body = $this->callGinger("GET", "/testlogin/cotisations", "key=validAppKey", [""]);
        $this->callGinger("PUT", "/testlogin2/cotisations/" . $body[0]["id"], "key=validAppKey", $body, 403);
    }

    protected function setUp(): void
    {
        $this->app = $this->getAppInstance();
        $this->db = $this->container->get(PDO::class);
        $this->db->exec("DELETE `m` FROM `memberships` `m` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` IN ('testlogin', 'testlogin2')");
        $this->db->exec("DELETE `c` FROM `cards` `c` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` IN ('testlogin', 'testlogin2')");
        $this->db->exec("DELETE FROM `users` WHERE `login` IN ('testlogin', 'testlogin2')");
        $this->db->exec("
                    INSERT INTO `users` (`login`, `prenom`, `nom`, `mail`, `type`, `is_adulte`, `created_at`, `last_access`)
                    VALUES ('testlogin', 'John', 'DOE', 'john.doe@etu.utc.fr', '0', '1', NOW(), NOW()),
                        ('testlogin2', 'John2', 'DOE', 'john.doe2@etu.utc.fr', '4', '1', NOW(), NOW())
                   ");
        $this->db->exec("
        INSERT INTO `memberships` (`user_id`, `debut`, `fin`, `montant`)
        SELECT `id`, '2018-01-01', '2120-12-31', '100' FROM `users` WHERE `login` IN ('testlogin', 'testlogin2')
    ");
    }

    protected function tearDown(): void
    {
        $this->db->exec("DELETE m FROM memberships m inner join users u on (u.id = user_id) WHERE login IN ('testlogin', 'testlogin2')");
        $this->db->exec("DELETE `c` FROM `cards` `c` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` IN ('testlogin', 'testlogin2')");
        $this->db->exec("DELETE FROM `users` WHERE `login` IN ('testlogin', 'testlogin2')");
    }
}
