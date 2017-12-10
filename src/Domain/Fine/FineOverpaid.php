<?php
declare(strict_types=1);

namespace Library\Domain\Fine;

use Library\Domain\AggregateChanged;
use Library\Domain\LendingId;

final class FineOverpaid extends AggregateChanged
{
    /** @var null|LendingId */
    private $lendingId;

    /** @var null|float */
    private $overpayAmount;

    public static function occured(LendingId $lendingId, float $overpayAmount): self
    {
        return self::occur($lendingId->toScalar(), [
            'overpayAmount' => $overpayAmount
        ]);
    }

    public function lendingId(): LendingId
    {
        if (null === $this->lendingId) {
            $this->lendingId = new LendingId($this->aggregateId());
        }

        return $this->lendingId;
    }

    public function overpayAmount(): float
    {
        if (null === $this->overpayAmount) {
            $this->overpayAmount = $this->payload['overpayAmount'];
        }

        return $this->overpayAmount;
    }
}
