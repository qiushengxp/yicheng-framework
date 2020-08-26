<?php
/**
 * @Author:wa.huang
 * @CreateDate: 2020/8/25 1:39 下午
 */

namespace Yicheng\Framework\service;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\FileCacheReader;
use Yicheng\Framework\annotations\Permissions;

class PermissionsService
{
    /**
     * 注解缓存读取器
     * @var FileCacheReader
     */
    protected $reader;
    /**
     * 注解缓存目录
     * @var string
     */
    private $annotationCacheDir;
    /**
     * 注解调试，建议常开
     * @var bool
     */
    private $annotationDebug = true;

    /**
     * 读取类
     * @var \ReflectionClass
     */
    private $reflectionClass;

    /**
     * 权限名数据
     * ['user:index','user:add','user:update']
     * @var array
     */
    private $permissionData = [];

    /**
     * 角色名
     * @var array
     */
    private $roles = [];

    public function __construct($class = '')
    {
        $this->annotationCacheDir = runtime_path() . 'annotation' . DIRECTORY_SEPARATOR . 'log';

        AnnotationRegistry::registerLoader('class_exists');
        // 注解缓存读取器
        $this->reader = new FileCacheReader(new AnnotationReader(), $this->annotationCacheDir, $this->annotationDebug);
        // 设置读取类
        if (empty($class)) {
            $this->setClass($class);
        }
    }

    /**
     * 设置操作类
     * @param string $class
     * @return $this
     * @throws \ReflectionException
     */
    public function setClass(string $class)
    {
        // 获取类和方法的注释信息
        $this->reflectionClass = new \ReflectionClass($class);
        return $this;
    }

    /**
     * 设置权限
     * @param array $data
     */
    public function setPermissions(array $data)
    {
        $this->permissionData = $data;
        return $this;
    }

    /**
     * 设置角色名
     * @param array $data
     */
    public function setRole(array $data)
    {
        $this->roles = $data;
        return $this;
    }

    /**
     * 验证权限是否通过
     * @param string $method
     * @return bool
     * @throws \ReflectionException
     */
    public function checkPermission(string $method)
    {
        if (empty($this->reflectionClass)) {
            return true;
        }
        $m          = $this->reflectionClass->getMethod($method);
        $annotation = $this->reader->getMethodAnnotation($m, Permissions::class);
        // 不存在注解则直接通过验证
        if (empty($annotation)) {
            return true;
        }
        // 注解权限名权限
        $name = true;
        if (!empty($annotation->name)) {
            if (!in_array($annotation->name, $this->permissionData)) {
                $name = false;
            }
        }
        // 注解角色权限
        $role = true;
        if (!empty($annotation->role)) {
            $roles = (array)$annotation->role;
            // 角色注解支持数组
            foreach ($roles as $vo) {
                $role = false;
                if (in_array($vo, $this->roles)) {
                    $role = false;
                }
            }

        }

        return $name && $role;
    }
}