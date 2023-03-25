<?php
declare(strict_types=1);

namespace SIMDE\Ginger\Test\Action;

use PDO;
use SIMDE\Ginger\Test\TestCase;


class MembershipReadTest extends TestCase
{
    protected $app;
    protected $db;

    public function testUnknownLogin(): void
    {
        $responseContent = $this->callGinger("GET", "/falsy/cotisations", "key=validAppKey", null, 404);
        $this->assertSame("User not found", $responseContent["error"]["message"]);
        $this->assertSame(404, $responseContent["error"]["code"]);
    }

    public function testKnownLoginWithoutMembership(): void
    {
        $responseContent = $this->callGinger("GET", "/testlogin/cotisations", "key=validAppKey");
        $this->assertEmpty($responseContent);

        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertFalse($responseContent["is_cotisant"]);
    }

    public function testKnownLoginWithMembership(): void
    {
        $this->db->exec("INSERT INTO `memberships` (`user_id`, `debut`, `fin`, `montant`, `created_at`, `deleted_at`)
      SELECT `users`.`id`, NOW(), '2050-12-31', '20', NOW(), NULL
      FROM `users`
      WHERE `login`='testlogin';
    ");
        $responseContent = $this->callGinger("GET", "/testlogin/cotisations", "key=validAppKey");
        $this->assertCount(1, $responseContent);
        $this->assertCount(4, $responseContent[0]);
        $this->assertSame("20", (string)$responseContent[0]["montant"]);
        $this->assertSame("2050-12-31", $responseContent[0]["fin"]);

        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertTrue($responseContent["is_cotisant"]);
    }

    public function testKnownLoginWithDeletedMembership(): void
    {
        $this->db->exec("INSERT INTO `memberships` (`user_id`, `debut`, `fin`, `montant`, `created_at`, `deleted_at`)
      SELECT `id`, NOW(), '2050-12-31', '20', NOW(), NOW()
      FROM `users`
      WHERE `login`='testlogin';
    ");
        $responseContent = $this->callGinger("GET", "/testlogin/cotisations", "key=validAppKey");
        $this->assertEmpty($responseContent);

        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertFalse($responseContent["is_cotisant"]);
    }

    public function testKnownLoginWithExpiredMembership(): void
    {
        $this->db->exec("INSERT INTO `memberships` (`user_id`, `debut`, `fin`, `montant`, `created_at`, `deleted_at`)
      SELECT `id`, '1994-01-27', '1994-01-28', '20', NOW(), NULL
      FROM `users`
      WHERE `login`='testlogin';
    ");
        $responseContent = $this->callGinger("GET", "/testlogin/cotisations", "key=validAppKey");
        $this->assertCount(1, $responseContent);
        $this->assertCount(4, $responseContent[0]);
        $this->assertSame("20", (string)$responseContent[0]["montant"]);

        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertFalse($responseContent["is_cotisant"]);
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
        $this->db->exec("DELETE `m` FROM `memberships` `m` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` = 'testlogin'");
        $this->db->exec("DELETE `c` FROM `cards` `c` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` = 'testlogin'");
        $this->db->exec("DELETE FROM `users` WHERE `login` = 'testlogin'");
    }

}
