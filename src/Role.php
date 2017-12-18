<?php

declare(strict_types=1);

namespace Choredo;

class Role
{
    const OWNER = 'owner';

    /**
     * @var string
     */
    private $name;

    /**
     * @var string[]
     */
    private $permissions;

    /**
     * Role constructor.
     *
     * @param string   $name
     * @param string[] $permissions
     */
    private function __construct(string $name, array $permissions = [])
    {
        $this->name        = $name;
        $this->permissions = $permissions;
    }

    /**
     * @param string $roleName
     *
     * @return Role
     */
    public static function create(string $roleName): self
    {
        if (static::exists($roleName)) {
            return static::$roleName();
        }

        throw new \InvalidArgumentException("Role '{$roleName}' is invalid or undefined");
    }

    /**
     * @param string $roleName
     *
     * @return bool
     */
    public static function exists(string $roleName): bool
    {
        return method_exists(static::class, $roleName);
    }

    /**
     * @return Role
     */
    public static function owner(): self
    {
        return new static(static::OWNER, [
            Permissions::CAN_MANAGE_ACCOUNT,
            Permissions::CAN_MANAGE_CHILDREN,
            Permissions::CAN_MANAGE_FAMILY,
        ]);
    }

    /**
     * @param string $permission
     *
     * @return bool
     */
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->permissions, true);
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string[]
     */
    public function getPermissions(): array
    {
        return $this->permissions;
    }
}
