<?php
declare(strict_types=1);

namespace Library\Domain;

abstract class AggregateChanged extends \Prooph\EventSourcing\AggregateChanged
{
    protected function setPayload(array $payload): void
    {
        $this->payload = $payload;
    }
}
