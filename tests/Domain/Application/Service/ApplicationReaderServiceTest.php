<?php

namespace SIMDE\Ginger\Test\Domain\Application\Service;

use PHPUnit\Framework\TestCase;
use SIMDE\Ginger\Domain\Application\Data\Application;
use SIMDE\Ginger\Domain\Application\Data\Permission;
use SIMDE\Ginger\Domain\Application\Repository\ApplicationRepository;
use SIMDE\Ginger\Domain\Application\Service\ApplicationReaderService;
use SIMDE\Ginger\Exception\ForbiddenException;

class ApplicationReaderServiceTest extends TestCase
{
    public function testGetApplicationByKey(): void
    {
        $this->assertTrue(true);
    }

    /**
     * @dataProvider providerPermissionsListData
     */
    public function testCheckPermissions($permissions, $shouldThrowForbidden, $orMode): void
    {
        $application = new Application();
        $application->permissions = [];
        foreach ($permissions as $permission) {
            $p = new Permission();
            $p->name = $permission;
            $application->permissions[] = $p;
        }
        $applicationRepository = $this->createMock(ApplicationRepository::class);
        $applicationReaderService = new ApplicationReaderService($applicationRepository);
        if ($shouldThrowForbidden) {
            $this->expectException(ForbiddenException::class);
        } else {
            $this->expectNotToPerformAssertions();
        }
        $applicationReaderService->checkPermissions($application, [Permission::LOGIN_CAN_READ, Permission::MAIL_CAN_READ], $orMode);
    }

    /**
     * @dataProvider providerPermissionsData
     */
    public function testIsAllowed($permissions, $expected): void
    {
        $application = new Application();
        $application->permissions = [];
        foreach ($permissions as $permission) {
            $p = new Permission();
            $p->name = $permission;
            $application->permissions[] = $p;
        }
        $applicationRepository = $this->createMock(ApplicationRepository::class);
        $applicationReaderService = new ApplicationReaderService($applicationRepository);
        $this->assertEquals($expected, $applicationReaderService->isAllowed($application, Permission::LOGIN_CAN_READ));
    }

    public static function providerPermissionsData(): array
    {
        return [
            [[], false],
            [[Permission::LOGIN_CAN_READ], true],
            [[Permission::CARDS_CAN_READ], false],
            [[Permission::LOGIN_CAN_READ, Permission::MEMBERSHIPS_CAN_UPDATE], true],
            [[Permission::CARDS_CAN_READ_LIST, Permission::MEMBERSHIPS_CAN_UPDATE], false],

        ];
    }

    public static function providerPermissionsListData(): array
    {
        return [
            [[], true, false],
            [[Permission::LOGIN_CAN_READ], true, false],
            [[Permission::MAIL_CAN_READ], true, false],
            [[Permission::LOGIN_CAN_READ, Permission::MAIL_CAN_READ], false, false],
            [[Permission::MAIL_CAN_READ, Permission::CARDS_CAN_READ], true, false],
            [[], true, true],
            [[Permission::LOGIN_CAN_READ], false, true],
            [[Permission::MAIL_CAN_READ], false, true],
            [[Permission::LOGIN_CAN_READ, Permission::MAIL_CAN_READ], false, true],
            [[Permission::MAIL_CAN_READ, Permission::CARDS_CAN_READ], false, true],
        ];
    }
}
