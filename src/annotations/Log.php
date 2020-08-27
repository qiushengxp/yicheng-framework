<?php
/**
 * @Author:wa.huang
 * @CreateDate: 2020/8/19 5:55 下午
 */

namespace Yicheng\Framework\annotations;


use Doctrine\Common\Annotations\Annotation;
use Yicheng\framework\constant\BusinessType;

/**
 * Class LogAnnotations
 *
 * @Annotation
 * @Target("METHOD")
 * @Attributes({
 *     @Attribute("title", type="string"),
 * })
 *
 */
final class Log extends Annotation
{
    /**
     * 标题
     * @Required()
     * @var string
     */
    public $title;

    /**
     * 业务类型
     * @Enum({ADD,UPDATE,DELETE})
     * @var string
     */
    public $businessType;

    /**
     * 提交方式
     * 根据此类型过滤提交
     * @Enum({GET,POST,PUT,PATCH,DELETE,ALL})
     * @var string
     */
    public $method = "all";

}