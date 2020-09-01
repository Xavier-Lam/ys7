<?php
namespace Neteast\YS7\Policy;

/**
 * 策略
 */
class Statement
{
    public $permissions;
    public $resources;

    public static function create($permissions, $resources)
    {
        return new static($permissions, $resources);
    }

    /**
     * @param string[] $permissions
     * @param Resource[] $resources
     */
    public function __construct($permissions, $resources)
    {
        $this->permissions = $permissions;
        $this->resources = $resources;
    }

    public function data()
    {
        return [
            'Permission' => implode(",", $this->permissions),
            'Resource' => array_map(str, $this->resources)
        ];
    }
}