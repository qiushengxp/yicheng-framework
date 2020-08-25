<?php
/**
 * @Author:wa.huang
 * @CreateDate: 2020/8/21 9:26 上午
 */

namespace Yicheng\Framework\exception;



class ServiceException extends \Exception
{
    public function __construct($message = "", $code = 0) {
        $this->message = $message;
        $this->code = $code;
    }
}