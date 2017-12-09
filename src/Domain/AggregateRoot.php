<?php
declare(strict_types=1);

namespace Library\Domain;

use Prooph\EventSourcing\AggregateChanged;

abstract class AggregateRoot extends \Prooph\EventSourcing\AggregateRoot
{
    protected function apply(AggregateChanged $event): void
    {
        $handlerMethod = 'on' . implode(array_slice(explode('\\', get_class($event)), -1));

        if (!method_exists($this, $handlerMethod)) {
            throw new \RuntimeException(sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handlerMethod,
                get_class($this)
            ));
        }

        $this->{$handlerMethod}($event);
    }
}
