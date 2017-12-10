<?php
declare(strict_types=1);

namespace Library\Domain\Fine\CancellationRequest;

use Library\Domain\AggregateChanged;
use Library\Domain\LendingId;

final class FineCancellationRequestSubmitted extends AggregateChanged
{
    /** @var null|LendingId */
    private $lendingId;

    /** @var string */
    private $reason;

    public static function occured(LendingId $lendingId, string $reason): self
    {
        return self::occur($lendingId->toScalar(), [
            'reason' => $reason
        ]);
    }

    public function lendingId(): LendingId
    {
        if (null === $this->lendingId) {
            $this->lendingId = new LendingId($this->aggregateId());
        }

        return $this->lendingId;
    }

    public function reason(): string
    {
        if (null === $this->reason) {
            $this->reason = $this->payload['reason'];
        }

        return $this->reason;
    }
}