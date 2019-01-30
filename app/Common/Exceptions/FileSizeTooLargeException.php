<?php
/**
 * Created by PhpStorm.
 * User: zhiyu1205
 * Date: 2019-01-28
 * Time: 15:29
 */

namespace App\Common\Exceptions;


use Exception;
use Throwable;

class FileSizeTooLargeException extends Exception
{
    public function __construct(string $message = "", int $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function __toString()
    {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }

    public function getFileTooLargeMessage()
    {
        return 'File must not be larger than 1 mb';
    }
}