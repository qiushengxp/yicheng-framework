<?php
/**
 * @Author:wa.huang
 * @CreateDate: 2020/8/25 11:54 上午
 */

namespace Yicheng\Framework\annotations;


use Doctrine\Common\Annotations\Annotation;

/**
 * Class Permissions
 * 权限注解
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("name", type="string"),
 *     @Attribute("role", type="array"),
 * })
 *
 */
final class Permissions extends Annotation
{
    /**
     * 权限名
     * @var string
     */
    public $name;

    /**
     * 角色名
     * @var array
     */
    public $role;
}