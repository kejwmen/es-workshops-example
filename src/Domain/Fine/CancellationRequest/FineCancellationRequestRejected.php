<?php
declare(strict_types=1);

namespace Library\Domain\Fine\CancellationRequest;

use Library\Domain\AggregateChanged;
use Library\Domain\LendingId;

final class FineCancellationRequestRejected extends AggregateChanged
{
    /** @var null|LendingId */
    private $lendingId;

    public static function occured(LendingId $lendingId): self
    {
        return self::occur($lendingId->toScalar());
    }

    public function lendingId(): LendingId
    {
        if (null === $this->lendingId) {
            $this->lendingId = new LendingId($this->aggregateId());
        }

        return $this->lendingId;
    }
}