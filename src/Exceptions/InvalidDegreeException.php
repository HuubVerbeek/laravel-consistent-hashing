<?php

namespace HuubVerbeek\ConsistentHashing\Exceptions;

use Throwable;

class InvalidDegreeException extends \Exception
{
    /**
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if (empty($message)) {
            $this->message = $this->defaultMessage();
        }
    }

    /**
     * @return string
     */
    public function defaultMessage(): string
    {
        return 'The passed in value is not a valid degree. The value should be between 0 and 359';
    }
}
