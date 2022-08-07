<?php

namespace HuubVerbeek\ConsistentHashing\Exceptions;

use Throwable;

class NodeCollectionException extends \Exception
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
        return 'A node collection only accepts an array of items of the type HuubVerbeek\ConsistentHashingCache\Node';
    }
}
