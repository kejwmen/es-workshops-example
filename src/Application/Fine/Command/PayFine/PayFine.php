<?php
declare(strict_types=1);

namespace Library\Application\Fine\Command\PayFine;

use Library\Domain\LendingId;

final class PayFine
{
    /** @var LendingId */
    private $lendingId;

    /** @var float */
    private $amount;

    public function __construct(LendingId $lendingId, float $amount)
    {
        $this->lendingId = $lendingId;
        $this->amount = $amount;
    }

    public function lendingId(): LendingId
    {
        return $this->lendingId;
    }

    public function amount(): float
    {
        return $this->amount;
    }
}
