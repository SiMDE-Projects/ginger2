<?php

namespace SIMDE\Ginger\Test\Domain\Membership\Service;

use Exception;
use PDO;
use SIMDE\Ginger\Domain\Membership\Data\Membership;
use SIMDE\Ginger\Domain\Membership\Repository\MembershipReaderRepository;
use SIMDE\Ginger\Domain\Membership\Service\MembershipReader;
use SIMDE\Ginger\Domain\User\Data\User;
use SIMDE\Ginger\Test\TestCase;
use Slim\App;

class MembershipReaderTest extends TestCase
{
    protected App $app;
    protected PDO $db;

    public function testGetMembershipsByUser(): void
    {
        $r = new MembershipReaderRepository($this->db);
        $s = new MembershipReader($r);
        $user = new User();
        $this->db = $this->container->get(PDO::class);
        $user->id = $this->db->query("SELECT id FROM users WHERE `login` = 'testlogin'")->fetch()['id'];
        $this->assertCount(1, $s->getMembershipsByUser($user));
    }

    public function testGetMembershipsById(): void
    {
        $r = new MembershipReaderRepository($this->db);
        $s = new MembershipReader($r);
        $ids = $this->db->query("
            SELECT memberships.* FROM memberships INNER JOIN users WHERE login = 'testlogin' LIMIT 1
        ")->fetch();
        $expected = new Membership();
        $expected->id = $ids["id"];
        $expected->user_id = $ids["user_id"];
        $expected->debut = $ids["debut"];
        $expected->fin = $ids["fin"];
        $expected->montant = $ids["montant"];
        $expected->created_at = $ids["created_at"];
        $expected->deleted_at = $ids["deleted_at"];
        $this->assertEquals($expected, $s->getMembershipById($ids["id"]));
    }

    /**
     * @throws Exception
     */
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
                        ('testlogin2', 'John2', 'DOE', 'john.doe2@etu.utc.fr', '0', '1', NOW(), NOW())
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
