<?php

namespace App\Domain\Application\Repository;

use App\Exception\ValidationException;
use App\Exception\ApplicationNotFoundException;
use App\Domain\Application\Data\Application;
use App\Domain\Application\Data\Permission;
use App\Domain\Application\Data\Role;
use PDO;

class ApplicationRepository
{
    private $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }
    
    public function getApplicationByKey(string $key): Application {
      $statement = $this->connection->prepare("SELECT * FROM `applications` WHERE `key` = :key ;");
      $statement->execute(['key' => $key]);
      $app = $statement->fetch();
      if (!$app) {
          throw new ApplicationNotFoundException("Application not found by key in db");
      }
      
      $result = $this->buildApplicationObject($app);
      $result->permissions = $this->getApplicationPermissions($result);
      
      return $result;
    }
    
    private function getApplicationPermissions(Application $app): array
    {
      $statement = $this->connection->prepare("SELECT DISTINCT p1.name FROM application_permissions ap INNER JOIN permissions p1 ON p1.id = ap.permission WHERE application = :app UNION SELECT DISTINCT p2.name FROM application_roles ar INNER JOIN roles r ON r.id = ar.role INNER JOIN role_permissions rp ON r.id = rp.role INNER JOIN permissions p2 ON p2.id = rp.permission WHERE application = :app ;");
      $statement->execute(['app' => $app->id]);
      $result = [];
      foreach ($statement->fetchAll()?:[] as $permission) {
        $result[] = $this->buildPermissionObject($permission);
      };
      return $result;
    }
    
    private function buildApplicationObject($row): Application {
        $app = new Application();
        $app->id = (int)$row['id'];
        $app->name = (string)$row['name'];
        $app->owner = (string)$row['owner'];
        $app->key = (string)$row['key'];
        $app->created_at = $row['created_at'];
        $app->last_access = $row['last_access'];
        $app->removed_at = $row['removed_at'];
        $app->permissions = [];
        return $app;
    }
    
    private function buildRoleObject($row): Role {
        $role = new Role();
        $role->id = (int)$row['id'];
        $role->name = (string)$row['name'];
        $role->description = (string)$row['description'];
        $role->created_at = $row['created_at'];
        return $role;
    }
    
    private function buildPermissionObject($row): Permission {
        $permission = new Permission();
        $permission->id = (int)$row['id'];
        $permission->name = (string)$row['name'];
        $permission->description = (string)$row['description'];
        $permission->created_at = $row['created_at'];
        return $permission;
    }
}
