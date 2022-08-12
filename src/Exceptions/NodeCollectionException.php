<?php

namespace HuubVerbeek\ConsistentHashing\Exceptions;

use Throwable;

class NodeCollectionException extends \Exception
{
    /**
     * @param  string  $message
     * @param  int  $code
     * @param  Throwable|null  $previous
     */
    public function __construct(string $message = '', int $code = 0, ?Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);

        $this->message = empty($message)
            ? $this->defaultMessage()
            : "The collections requires nodes that are an instance of {$message}";
    }

    /**
     * @return string
     */
    public function defaultMessage(): string
    {
        return 'A node collection only accepts an array of items of the type HuubVerbeek\ConsistentHashingCache\Node';
    }
}
