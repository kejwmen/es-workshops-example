<?php
declare(strict_types=1);

namespace Library\Domain\Fine;

use Library\Domain\AggregateChanged;
use Library\Domain\LendingId;

final class FineIssued extends AggregateChanged
{
    /** @var null|LendingId */
    private $lendingId;

    /** @var null|float */
    private $amount;

    public static function occured(LendingId $lendingId, float $amount): self
    {
        return self::occur($lendingId->toScalar(), [
            'amount' => $amount
        ]);
    }

    public function lendingId(): LendingId
    {
        if (null === $this->lendingId) {
            $this->lendingId = new LendingId($this->aggregateId());
        }

        return $this->lendingId;
    }

    public function amount(): float
    {
        if (null === $this->amount) {
            $this->amount = $this->payload['amount'];
        }

        return $this->amount;
    }
}
