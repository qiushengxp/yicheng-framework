<?php
/**
 * @Author:wa.huang
 * @CreateDate: 2020/8/20 5:19 下午
 */

namespace Yicheng\Framework\service;


use Doctrine\Common\Annotations\AnnotationReader;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Doctrine\Common\Annotations\FileCacheReader;
use think\facade\Db;
use Yicheng\Framework\annotations\LogAnnotations;
use Yicheng\Framework\exception\ServiceException;

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
     * 数据表名
     * 根据模型规则，忽略表前缀
     * @var string
     */
    private $table = '';

    /**
     *
     * LogService constructor.
     * @param string $class
     */
    public function __construct($class = '', $table = '')
    {
        $this->annotationCacheDir = runtime_path() . 'annotation' . DIRECTORY_SEPARATOR . 'log';

        AnnotationRegistry::registerLoader('class_exists');
        // 注解缓存读取器
        $this->reader = new FileCacheReader(new AnnotationReader(), $this->annotationCacheDir, $this->annotationDebug);
        // 设置读取类
        if (empty($class)) {
            $this->setClass($class);
        }
        // 设置表名
        if (empty($table)) {
            $this->setTable($table);
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
     * 设置表名
     * @param string $table
     * @return $this
     */
    public function setTable(string $table)
    {
        $this->table = $table;
        return $this;
    }

    /**
     * 获取方法的注解信息
     * @param string $method
     * @return $this
     * @throws \ReflectionException
     */
    public function getMethod(string $method)
    {
        $m          = $this->reflectionClass->getMethod($method);
        $annotation = $this->reader->getMethodAnnotation($m, LogAnnotations::class);
        // 若存在注解，则返回注解信息
        if (!empty($annotation)) {
            $this->setData($annotation);
        }
        return $this;
    }

    /**
     * 返回属性
     * @param string $property
     * @return $this
     * @throws \ReflectionException
     */
    public function getProperty(string $property)
    {
        $m          = $this->reflectionClass->getProperty($property);
        $annotation = $this->reader->getPropertyAnnotation($m, LogAnnotations::class);
        // 若存在注解，则返回注解信息
        if (!empty($annotation)) {
            $this->setData($annotation);
        }
        return $this;
    }

    /**
     * 设置注解信息
     * @param $annotation
     */
    private function setData($annotation)
    {
        $this->data = array_merge($this->data, [
            'title'        => $annotation->title,
            'action'       => $annotation->action,
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

    /**
     * 保存数据
     * @param array $data
     * @return int|string
     * @throws ServiceException
     */
    public function save($data = [])
    {
        if (!empty($data)) {
            $this->data = $data;
        }
        if (empty($this->table)) {
            throw new ServiceException('未设置数据表名');
        }
        if (empty($this->data)) {
            throw new ServiceException('未指定需要保存的数据');
        }
        $re = Db::name($this->table)->insert($this->data);
        if ($re) {
            $this->data = [];   // 清空数据
            return $re;
        } else {
            throw new ServiceException('保存失败');
        }
    }
}