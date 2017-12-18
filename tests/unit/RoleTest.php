<?php

declare(strict_types=1);

namespace Choredo\Test;

use Choredo\Permissions;
use Choredo\Role;
use PHPUnit\Framework\TestCase;

class RoleTest extends TestCase
{
    public function testRoleThrowsExceptionForInvalidRoleName()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("Role 'foo' is invalid or undefined");
        Role::create('foo');
    }

    public function testRoleExists()
    {
        $this->assertFalse(Role::exists('foo'));
        $this->assertTrue(Role::exists(Role::OWNER));
    }

    public function testOwnerFactoryReturnsValidOwnerRole()
    {
        $role = Role::owner();
        $this->assertEquals(Role::OWNER, $role->getName());
        $this->assertCount(3, $role->getPermissions());
        $this->assertTrue($role->hasPermission(Permissions::CAN_MANAGE_FAMILY));
        $this->assertTrue($role->hasPermission(Permissions::CAN_MANAGE_ACCOUNT));
        $this->assertTrue($role->hasPermission(Permissions::CAN_MANAGE_CHILDREN));
    }
}
