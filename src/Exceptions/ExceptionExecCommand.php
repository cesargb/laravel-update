<?php
namespace Cesargb\Update\Exceptions;

use Exception;

class ExceptionExecCommand extends Exception
{
    public static function create(string $command, string $errno, string $output): ExceptionExecCommand
    {
        return new static("Error to execute `{$command}`: {$output} (errno {$errno}).");
    }
}
