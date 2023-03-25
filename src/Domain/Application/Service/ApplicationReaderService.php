<?php

namespace SIMDE\Ginger\Domain\Application\Service;

use SIMDE\Ginger\Domain\Application\Data\Application;
use SIMDE\Ginger\Domain\Application\Repository\ApplicationRepository;
use SIMDE\Ginger\Exception\ForbiddenException;

final class ApplicationReaderService
{
    private ApplicationRepository $applicationRepository;

    public function __construct(ApplicationRepository $applicationRepository)
    {
        $this->applicationRepository = $applicationRepository;
    }

    public function getApplicationByKey(string $key): Application
    {
        return $this->applicationRepository->getApplicationByKey($key);
    }

    public function checkPermissions(Application $application, array $permissionsToCheck, bool $modeOr = false): void
    {
        if ($modeOr) {
            foreach ($permissionsToCheck as $permissionToCheck) {
                if ($this->isAllowed($application, $permissionToCheck)) {
                    return;
                }
            }
            throw new ForbiddenException("Missing permission for this application");
        }
        foreach ($permissionsToCheck as $permissionToCheck) {
            if (!$this->isAllowed($application, $permissionToCheck)) {
                throw new ForbiddenException("Missing permission for this application");
            }
        }
    }

    public function isAllowed(Application $application, string $permissionToCheck): bool
    {
        foreach ($application->permissions as $permission) {
            if ($permission->name === $permissionToCheck) {
                return true;
            }
        }
        return false;
    }
}
