<?php
declare(strict_types=1);

namespace SIMDE\Ginger\Test\Action;

use PDO;
use SIMDE\Ginger\Test\TestCase;
use Slim\App;

class UserReadActionTest extends TestCase
{
    protected App $app;
    protected PDO $db;

    public function setUp(): void
    {
        $this->app = $this->getAppInstance();
        $this->db  = $this->container->get(PDO::class);
        $this->db->exec("DELETE `m` FROM `memberships` `m` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` IN ('testlogin', 'escompers', 'escomlogin', 'perslogin', 'unknownprofiletype', 'sejournant')");
        $this->db->exec("DELETE `c` FROM `cards` `c` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` IN ('testlogin', 'escompers', 'escomlogin', 'perslogin', 'unknownprofiletype', 'sejournant')");
        $this->db->exec("DELETE `uo` FROM `user_overrides` `uo` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` IN ('testlogin', 'escompers', 'escomlogin', 'perslogin', 'unknownprofiletype', 'sejournant')");
        $this->db->exec("DELETE FROM `users` WHERE `login` IN ('testlogin', 'escompers', 'escomlogin', 'perslogin', 'unknownprofiletype', 'sejournant')");
    }

    protected function tearDown(): void
    {
        $this->db->exec("DELETE `m` FROM `memberships` `m` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` IN ('testlogin', 'escompers', 'escomlogin', 'perslogin', 'unknownprofiletype', 'sejournant')");
        $this->db->exec("DELETE `c` FROM `cards` `c` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` IN ('testlogin', 'escompers', 'escomlogin', 'perslogin', 'unknownprofiletype', 'sejournant')");
        $this->db->exec("DELETE `uo` FROM `user_overrides` `uo` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` IN ('testlogin', 'escompers', 'escomlogin', 'perslogin', 'unknownprofiletype', 'sejournant')");
        $this->db->exec("DELETE FROM `users` WHERE `login` IN ('testlogin', 'escompers', 'escomlogin', 'perslogin', 'unknownprofiletype', 'sejournant')");
    }

    public function testUnknownKey(): void
    {
        $responseContent = $this->callGinger("GET", "/falsy", "key=unknownkey", null, 404);
        $this->assertSame("Application not found by key in db", $responseContent["error"]["message"]);
        $this->assertSame(404, $responseContent["error"]["code"]);
        $this->assertSame("Application not found by key in db", $responseContent["error"]["detail"]["message"]);
        $this->assertSame(404, $responseContent["error"]["detail"]["errcode"]);
    }

    public function testDisabledKey(): void
    {
        $responseContent = $this->callGinger("GET", "/falsy", "key=removedAppKey", null, 404);
        $this->assertSame("Application not found by key in db", $responseContent["error"]["message"]);
        $this->assertSame(404, $responseContent["error"]["code"]);
        $this->assertSame("Application not found by key in db", $responseContent["error"]["detail"]["message"]);
        $this->assertSame(404, $responseContent["error"]["detail"]["errcode"]);
    }

    public function testMissingPermission(): void
    {
        $this->db->exec("INSERT INTO `applications` (`key`, `name`, `owner`, `created_at`, `last_access`, `removed_at`)
      VALUES ('tempkey', 'Test app', 'simde_test', NOW(), NOW(), NULL)
    ");
        $responseContent = $this->callGinger("GET", "/falsy", "key=tempkey", null, 403);
        $this->assertSame("Forbidden", $responseContent["error"]["message"]);
        $this->assertSame(403, $responseContent["error"]["code"]);
        $this->assertSame("Missing permission for this application", $responseContent["error"]["detail"]["message"]);
        $this->assertSame(403, $responseContent["error"]["detail"]["errcode"]);
        $this->db->exec("DELETE FROM `applications` WHERE `key` = 'tempkey'");
    }

    public function testReadCardsList(): void
    {
        $this->db->exec("INSERT INTO `application_permissions` (`application`, `permission`)
      VALUES ('1', '6');
    ");
        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertCount(2, $responseContent["cards"]);
        $this->assertSame("AABBCCDDEEFF", $responseContent["cards"][0]["uid"]);
        $this->assertSame(1, $responseContent["cards"][0]["type"]);
        $this->assertSame("A1B2C3D4", $responseContent["cards"][1]["uid"]);
        $this->assertSame(0, $responseContent["cards"][1]["type"]);
        $this->db->exec("DELETE FROM `application_permissions`
      WHERE `application` = '1' AND `permission` = '6'
    ");
    }

    public function testNoKey(): void
    {
        $responseContent = $this->callGinger("GET", "/falsy", "", null, 401);
        $this->assertSame("Unauthorized", $responseContent["error"]["message"]);
        $this->assertSame(401, $responseContent["error"]["code"]);
        $this->assertSame("Api key is missing", $responseContent["error"]["detail"]["message"]);
        $this->assertSame(401, $responseContent["error"]["detail"]["errcode"]);
    }

    public function testLoginNotFound(): void
    {
        $responseContent = $this->callGinger("GET", "/falsy", "key=validAppKey", null, 404);
        $this->assertSame("User not found", $responseContent["error"]["message"]);
        $this->assertSame(404, $responseContent["error"]["code"]);
        $this->assertSame("User not found", $responseContent["error"]["detail"]["message"]);
        $this->assertSame(404, $responseContent["error"]["detail"]["errcode"]);
    }

    public function testMailNotFound(): void
    {
        $responseContent = $this->callGinger("GET", "/mail/falsymail@etu.utc.fr", "key=validAppKey", null, 404);
        $this->assertSame("User not found by mail in db", $responseContent["error"]["message"]);
        $this->assertSame(404, $responseContent["error"]["code"]);
        $this->assertSame("User not found by mail in db", $responseContent["error"]["detail"]["message"]);
        $this->assertSame(404, $responseContent["error"]["detail"]["errcode"]);
    }

    public function testAccountWSError(): void
    {
        $responseContent = $this->callGinger("GET", "/generate_account_500", "key=validAppKey", null, 500);
        $this->assertSame("Accounts exception", $responseContent["error"]["message"]);
        $this->assertSame(500, $responseContent["error"]["code"]);
    }

    public function testCardNotFound(): void
    {
        $responseContent = $this->callGinger("GET", "/badge/invalidCard", "key=validAppKey", null, 404);
        $this->assertSame("User not found", $responseContent["error"]["message"]);
        $this->assertSame(404, $responseContent["error"]["code"]);
        $this->assertSame("User not found", $responseContent["error"]["detail"]["message"]);
        $this->assertSame(404, $responseContent["error"]["detail"]["errcode"]);
    }

    public function testCardFound(): void
    {
        $responseContent = $this->callGinger("GET", "/badge/AABBCCDDEEFF", "key=validAppKey");
        $this->assertSame("testlogin", $responseContent["login"]);
        $this->assertSame("AABBCCDDEEFF", $responseContent["badge_uid"]);
    }

    public function testLoginFound(): void
    {
        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertSame("testlogin", $responseContent["login"]);
        $this->assertSame("john.doe@etu.utc.fr", $responseContent["mail"]);
        $this->assertSame("DOE", $responseContent["nom"]);
        $this->assertSame("John", $responseContent["prenom"]);
        $this->assertSame("AABBCCDDEEFF", $responseContent["badge_uid"]);
        $this->assertSame("etu", $responseContent["type"]);
    }

    public function testLoginPersUtcFound(): void
    {
        $responseContent = $this->callGinger("GET", "/perslogin", "key=validAppKey");
        $this->assertSame("pers", $responseContent["type"]);
    }

    public function testLoginFoundWithUnhandledDSIProfile(): void
    {
        $responseContent = $this->callGinger("GET", "/unknownprofiletype", "key=validAppKey");
        $this->assertSame("error", $responseContent["type"]);
    }

    public function testLoginEscomFound(): void
    {
        $responseContent = $this->callGinger("GET", "/escomlogin", "key=validAppKey");
        $this->assertSame("escom", $responseContent["type"]);
    }

    public function testLoginSejournantFound(): void
    {
        $responseContent = $this->callGinger("GET", "/sejournant", "key=validAppKey");
        $this->assertSame("sejournant", $responseContent["type"]);
    }

    public function testLoginEscomPersFound(): void
    {
        $responseContent = $this->callGinger("GET", "/escompers", "key=validAppKey");
        $this->assertSame("escompers", $responseContent["type"]);
    }

    public function testMailFound(): void
    {
        $this->db->exec("INSERT INTO `users` (`login`, `prenom`, `nom`, `mail`, `type`, `is_adulte`, `created_at`, `last_access`)
      VALUES ('testlogin', 'John', 'DOE', 'john.doe@etu.utc.fr', '0', '1', NOW(), NOW())
    ");
        $responseContent = $this->callGinger("GET", "/mail/john.doe@etu.utc.fr", "key=validAppKey");
        $this->assertSame("testlogin", $responseContent["login"]);
        $this->assertSame("john.doe@etu.utc.fr", $responseContent["mail"]);
        $this->assertSame("DOE", $responseContent["nom"]);
        $this->assertSame("John", $responseContent["prenom"]);
        $this->assertSame("AABBCCDDEEFF", $responseContent["badge_uid"]);
    }

    public function testPartialFound(): void
    {
        $this->db->exec("INSERT INTO `users` (`login`, `prenom`, `nom`, `mail`, `type`, `is_adulte`, `created_at`, `last_access`)
      VALUES ('testlogin', 'John', 'DOE', 'john.doe@etu.utc.fr', '0', '1', NOW(), NOW())
    ");
        $responseContent = $this->callGinger("GET", "/find/john.doe", "key=validAppKey");
        $this->assertCount(1, $responseContent);
        $this->assertCount(4, $responseContent[0]);
        $this->assertSame("testlogin", $responseContent[0]["login"]);
        $this->assertSame("john.doe@etu.utc.fr", $responseContent[0]["mail"]);
        $this->assertSame("DOE", $responseContent[0]["nom"]);
        $this->assertSame("John", $responseContent[0]["prenom"]);
        $this->assertNull($responseContent[0]["badge_uid"]);
    }

    public function testOverride(): void
    {
        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertSame("testlogin", $responseContent["login"]);
        $this->assertSame("john.doe@etu.utc.fr", $responseContent["mail"]);
        $this->assertSame("DOE", $responseContent["nom"]);
        $this->assertSame("John", $responseContent["prenom"]);
        $this->assertSame("AABBCCDDEEFF", $responseContent["badge_uid"]);
        $this->db->exec("INSERT INTO `user_overrides`
      (`user_id`, `prenom`, `nom`, `mail`, `card_uid`, `type`, `is_adulte`, `created_at`, `ignored_at`)
      SELECT `id`, 'OP', 'ON', 'OM@mail.com', 'NEWCARD', '4', '0', NOW(), '2022-12-31 00:00:00'
      FROM `users`
      WHERE `login`='testlogin';
    ");
        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertSame("testlogin", $responseContent["login"]);
        $this->assertSame("OM@mail.com", $responseContent["mail"]);
        $this->assertSame("ON", $responseContent["nom"]);
        $this->assertSame("OP", $responseContent["prenom"]);
        $this->assertSame("NEWCARD", $responseContent["badge_uid"]);
        $this->assertSame("ext", $responseContent["type"]);
        $this->assertFalse($responseContent["is_adulte"]);
        $this->db->exec("UPDATE `user_overrides`
      INNER JOIN `users` ON `users`.`id` = `user_id`
      SET `ignored_at` = '2021-12-18 16:08:12'
      WHERE `login` = 'testlogin'
    ");
        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertSame("testlogin", $responseContent["login"]);
        $this->assertSame("john.doe@etu.utc.fr", $responseContent["mail"]);
        $this->assertSame("DOE", $responseContent["nom"]);
        $this->assertSame("John", $responseContent["prenom"]);
        $this->assertSame("AABBCCDDEEFF", $responseContent["badge_uid"]);
    }

    public function testCardSync(): void
    {
        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertSame("testlogin", $responseContent["login"]);
        $this->assertSame("john.doe@etu.utc.fr", $responseContent["mail"]);
        $this->assertSame("DOE", $responseContent["nom"]);
        $this->assertSame("John", $responseContent["prenom"]);
        $this->assertSame("AABBCCDDEEFF", $responseContent["badge_uid"]);
    }

    public function testCardSyncWithExistingCards(): void
    {
        // init user
        $this->callGinger("GET", "/testlogin", "key=validAppKey");
        // delete one of the two cards
        $this->db->exec("DELETE `c` FROM `cards` `c` INNER JOIN `users` `u` ON (`u`.`id` = `user_id`) WHERE `login` LIKE 'testlogin' AND `c`.`uid` LIKE 'AABBCCDDEEFF'");
        // call new sync
        $this->callGinger("GET", "/testlogin", "key=validAppKey");
    }

    public function testCardRemoving(): void
    {
        $this->db->exec("INSERT INTO `users` (`login`, `prenom`, `nom`, `mail`, `type`, `is_adulte`, `created_at`, `last_access`)
      VALUES ('testlogin', 'John', 'DOE', 'john.doe@assos.utc.fr', '0', '1', NOW(), NOW())
    ");
        $this->db->exec("INSERT INTO `cards` (`user_id`, `uid`, `type`, `created_at`)
      SELECT `id`, 'FALSYCARD', 2, NOW()
      FROM `users`
      WHERE `login`='testlogin'
    ");
        $responseContent = $this->callGinger("GET", "/testlogin", "key=validAppKey");
        $this->assertSame("AABBCCDDEEFF", $responseContent["badge_uid"]);
        $this->db->exec("DELETE FROM `cards` WHERE `uid` = 'FALSYCARD'");
    }

    public function testExtUserAccessAndUpdateByLogin(): void
    {
        $this->db->exec("DELETE FROM `users` WHERE `login` = 'extlogin'");
        $this->db->exec("INSERT INTO `users` (`login`, `prenom`, `nom`, `mail`, `type`, `is_adulte`, `created_at`, `last_access`)
      VALUES ('extlogin', 'EXT', 'EXT NAME', 'extmail@utc.fr', '4', '1', NOW(), NOW())
    ");
        $responseContent = $this->callGinger("GET", "/extlogin", "key=validAppKey");
        $this->assertSame("extlogin", $responseContent["login"]);
        $this->assertSame("extmail@utc.fr", $responseContent["mail"]);
        $this->assertSame("EXT NAME", $responseContent["nom"]);
        $this->assertSame("EXT", $responseContent["prenom"]);
        $this->assertNull($responseContent["badge_uid"]);

        $firstDate = $responseContent["last_access"];
        sleep(1);

        $responseContent = $this->callGinger("GET", "/extlogin", "key=validAppKey");
        $this->assertNotEquals($firstDate, $responseContent["last_access"]);
        $this->db->exec("DELETE FROM `users` WHERE `login` = 'extlogin'");
    }

    public function testExtUserAccessByMail(): void
    {
        $this->db->exec("DELETE FROM `users` WHERE `login` = 'extlogin'");
        $this->db->exec("INSERT INTO `users` (`login`, `prenom`, `nom`, `mail`, `type`, `is_adulte`, `created_at`, `last_access`)
      VALUES ('extlogin', 'EXT', 'EXT NAME', 'extmail@utc.fr', 4, 1, NOW(), NOW())
    ");
        $responseContent = $this->callGinger("GET", "/mail/extmail@utc.fr", "key=validAppKey");
        $this->assertSame("extlogin", $responseContent["login"]);
        $this->assertSame("extmail@utc.fr", $responseContent["mail"]);
        $this->assertSame("EXT NAME", $responseContent["nom"]);
        $this->assertSame("EXT", $responseContent["prenom"]);
        $this->assertNull($responseContent["badge_uid"]);

        $this->db->exec("DELETE FROM `users` WHERE `login` = 'extlogin'");
    }

    public function testExtUserAccessByCard(): void
    {
        $this->db->exec("DELETE FROM `cards` WHERE `uid` = 'FALSYEXTCARD'");
        $this->db->exec("DELETE FROM `users` WHERE `login` = 'extlogin'");
        $this->db->exec("INSERT INTO `users` (`login`, `prenom`, `nom`, `mail`, `type`, `is_adulte`, `created_at`, `last_access`)
      VALUES ('extlogin', 'EXT', 'EXT NAME', 'extmail@utc.fr', '4', '1', NOW(), NOW())
    ");
        $this->db->exec("INSERT INTO `cards` (`user_id`, `type`, `uid`, `created_at`, `removed_at`)
      SELECT `id`, 1, 'FALSYEXTCARD', NOW(), NULL
      FROM `users`
      WHERE `login`='extlogin';
    ");
        $responseContent = $this->callGinger("GET", "/badge/FALSYEXTCARD", "key=validAppKey");
        $this->assertSame("extlogin", $responseContent["login"]);
        $this->assertSame("extmail@utc.fr", $responseContent["mail"]);
        $this->assertSame("EXT NAME", $responseContent["nom"]);
        $this->assertSame("EXT", $responseContent["prenom"]);
        $this->assertSame("FALSYEXTCARD", $responseContent["badge_uid"]);

        $this->db->exec("DELETE FROM `cards` WHERE `uid` = 'FALSYEXTCARD'");
        $this->db->exec("DELETE FROM `users` WHERE `login` = 'extlogin'");
    }
}
