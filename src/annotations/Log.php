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
     * @var string
     */
    public $businessType;


}