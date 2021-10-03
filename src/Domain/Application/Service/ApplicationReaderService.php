<?php

namespace App\Domain\Application\Service;

use App\Domain\Application\Data\Application;
use App\Domain\Application\Repository\ApplicationRepository;
use App\Exception\ValidationException;

final class ApplicationReaderService
{
    private $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }
    
    public function getApplicationByKey(string $key): Application {
      return $this->applicationRepository->getApplicationByKey($key);
    }
    
    public function isAllowed(Application $application, string $permissionToCheck): bool {
      foreach ($application->permissions as $permission) {
        if($permission->name === $permissionToCheck){
          return true;
        }
      }
      return false;
    }
}
