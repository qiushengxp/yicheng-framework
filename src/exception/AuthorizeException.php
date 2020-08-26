<?php
/**
 * @Author:wa.huang
 * @CreateDate: 2020/8/25 5:28 下午
 */

namespace Yicheng\Framework\exception;


class AuthorizeException extends \Exception
{
    public function __construct($message = "未授权访问", $code = 401) {
        $this->message = $message;
        $this->code = $code;
    }
}