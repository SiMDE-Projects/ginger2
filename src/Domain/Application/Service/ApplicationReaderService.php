<?php

namespace SIMDE\Ginger\Domain\Application\Service;

use SIMDE\Ginger\Domain\Application\Data\Application;
use SIMDE\Ginger\Domain\Application\Repository\ApplicationRepository;
use SIMDE\Ginger\Exception\ValidationException;

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
