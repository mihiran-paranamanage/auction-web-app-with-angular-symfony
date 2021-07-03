<?php

declare(strict_types=1);

use App\Utility\UserRoleManager;
use PHPUnit\Framework\TestCase;

final class UserRoleManagerTest extends TestCase
{
    public function testGetPermissions() {
        $userRoleManagerMock = $this->getMockBuilder(UserRoleManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $accessToken = 'af874ho9s8dfush6';
        $result = $userRoleManagerMock->getPermissions($accessToken);
        $this->assertEquals(array(), $result);
    }

    public function testIsPermittedForDataGroup() {
        $userRoleManagerMock = $this->getMockBuilder(UserRoleManager::class)
            ->disableOriginalConstructor()
            ->getMock();
        $accessToken = 'df874ho9s8dfush9';
        $dataGroup = 'item';
        $permissionType = 'canDelete';
        $result = $userRoleManagerMock->isPermittedForDataGroup($accessToken, $dataGroup, $permissionType);
        $this->assertEquals(false, $result);
    }
}
