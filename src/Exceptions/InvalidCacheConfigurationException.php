<?php

namespace HuubVerbeek\ConsistentHashing\Exceptions;

use Throwable;

class InvalidCacheConfigurationException extends \Exception
{
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        if (empty($message)) {
            $this->message = $this->defaultMessage();
        }
    }

    public function defaultMessage(): string
    {
        return 'The passed in value is not a configured cache store.';
    }
}
