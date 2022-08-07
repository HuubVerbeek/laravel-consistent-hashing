<?php

namespace HuubVerbeek\ConsistentHashing\Exceptions;

use Throwable;

class NoNodesSetException extends \Exception
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
        return 'No nodes are set. Use the setNods(NodeCollection $nodes) to set them.';
    }
}
