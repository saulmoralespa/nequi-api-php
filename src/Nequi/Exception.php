<?php
/**
 * Created by PhpStorm.
 * User: smp
 * Date: 18/01/19
 * Time: 03:59 PM
 */

namespace Nequi;


class Exception extends \Exception
{
    public function __construct($message, $code = 0, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}