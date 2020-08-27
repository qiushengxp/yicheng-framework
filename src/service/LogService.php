<?php
/**
 * @Author:wa.huang
 * @CreateDate: 2020/8/20 5:19 下午
 */

namespace Yicheng\Framework\service;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\FileCacheReader;
use Yicheng\Framework\annotations\Log;

class LogService
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
     * 需要存储的数据
     * @var array
     */
    private $data = [];

    /**
     *
     * LogService constructor.
     * @param string $class
     */
    public function __construct($class = '')
    {
        $this->annotationCacheDir = runtime_path() . 'annotation' . DIRECTORY_SEPARATOR . 'log';

        AnnotationRegistry::registerLoader('class_exists');
        // 注解缓存读取器
        $this->reader = new FileCacheReader(new AnnotationReader(), $this->annotationCacheDir, $this->annotationDebug);
        // 设置读取类
        if (!empty($class)) {
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
        $controller         = str_replace('.', '\\', $class);
        // 获取类和方法的注释信息
        $this->reflectionClass = new \ReflectionClass($controller);
        return $this;
    }

    /**
     * 获取方法的注解信息
     * @param string $method
     * @return array|mixed|object|string|null
     * @throws \ReflectionException
     */
    public function getMethod(string $method)
    {
        if (empty($this->reflectionClass)) {
            return null;
        }
        $m          = $this->reflectionClass->getMethod($method);
        $annotation = $this->reader->getMethodAnnotation($m, Log::class);
        // 若存在注解，则返回注解信息
        if (!empty($annotation)) {
            $this->setData($annotation);
        }
        return $annotation;
    }

    /**
     * 设置注解信息
     * @param $annotation
     */
    private function setData($annotation)
    {
        $this->data = array_merge($this->data, [
            'title'        => $annotation->title,
            'businessType' => $annotation->businessType
        ]);
    }

    /**
     * 设置补充信息
     * @param $data
     * @return $this
     */
    public function setAdditional($data)
    {
        $this->data = array_merge($this->data, $data);
        return $this;
    }
}