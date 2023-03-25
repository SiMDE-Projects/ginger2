<?php

namespace SIMDE\Ginger\Domain\Application\Repository;

use DateTime;
use PDO;
use SIMDE\Ginger\Domain\Application\Data\Application;
use SIMDE\Ginger\Domain\Application\Data\Permission;
use SIMDE\Ginger\Exception\ApplicationNotFoundException;

class ApplicationRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getApplicationByKey(string $key): Application
    {
        $statement = $this->connection->prepare("SELECT * FROM `applications` WHERE `key` = :key AND `removed_at` IS NULL");
        $statement->execute(['key' => $key]);
        $app = $statement->fetch();
        if (!$app) {
            throw new ApplicationNotFoundException("Application not found by key in db");
        }

        $result = $this->buildApplicationObject($app);
        $result->permissions = $this->getApplicationPermissions($result);
        $this->updateLastAccessAttribute($result);

        return $result;
    }

    private function buildApplicationObject($row): Application
    {
        $app = new Application();
        $app->id = (int)$row['id'];
        $app->name = (string)$row['name'];
        $app->owner = (string)$row['owner'];
        $app->key = (string)$row['key'];
        $app->created_at = DateTime::createFromFormat("Y-m-d H:i:s", $row['created_at']);
        $app->last_access = DateTime::createFromFormat("Y-m-d H:i:s", $row['last_access']);
        $app->removed_at = $row['removed_at'] ? DateTime::createFromFormat("Y-m-d H:i:s", $row['removed_at']) : null;
        $app->permissions = [];
        return $app;
    }

    private function getApplicationPermissions(Application $app): array
    {
        $statement = $this->connection->prepare("SELECT DISTINCT `p1`.`name` FROM `application_permissions` `ap` INNER JOIN `permissions` `p1` ON `p1`.`id` = `ap`.`permission` WHERE `application` = :app UNION SELECT DISTINCT `p2`.`name` FROM `application_roles` `ar` INNER JOIN `roles` `r` ON `r`.`id` = `ar`.`role` INNER JOIN `role_permissions` `rp` ON `r`.`id` = `rp`.`role` INNER JOIN `permissions` `p2` ON `p2`.`id` = `rp`.`permission` WHERE `application` = :app");
        $statement->execute(['app' => $app->id]);
        $result = [];
        foreach ($statement->fetchAll() ?: [] as $permission) {
            $result[] = $this->buildPermissionObject($permission);
        }
        return $result;
    }

    private function buildPermissionObject($row): Permission
    {
        $permission = new Permission();
        $permission->id = (int)$row['id'];
        $permission->name = (string)$row['name'];
        $permission->description = (string)$row['description'];
        $permission->created_at = $row['created_at'];
        return $permission;
    }

    public function updateLastAccessAttribute(Application $applicationKey)
    {
        $sql = "UPDATE `applications` SET `last_access` = NOW() WHERE `key`=:key;";
        $statement = $this->connection->prepare($sql);
        $statement->execute(['key' => $applicationKey->key]);
    }
}
