<?php
namespace Raw\Component\Statistics\Exception;

class StatisticNotFoundException extends \Exception
{
    public function __construct($name, $code = 0, \Exception $previous = null)
    {
        $message = sprintf('Statistic "%s" does not exist', $name);
        parent::__construct($message, $code, $previous);
    }
}