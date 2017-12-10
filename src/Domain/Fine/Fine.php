<?php
declare(strict_types=1);

namespace Library\Domain\Fine;

use Assert\Assertion;
use Library\Domain\AggregateRoot;
use Library\Domain\LendingId;

class Fine extends AggregateRoot
{
    /** @var LendingId */
    private $lendingId;

    /** @var float */
    private $amount;

    protected function __construct()
    {
        $this->amount = 0.0;
    }

    public static function issue(LendingId $lendingId, float $amount): self
    {
        $s = new self();

        $s->recordThat(FineIssued::occured($lendingId, $amount));

        return $s;
    }

    public function pay(float $paidAmount): void
    {
        $this->throwIfNotDue();

        Assertion::true($paidAmount > 0, "Paid amount must be higher than 0");

        $amountBefore = $this->amount;

        $this->recordThat(FinePaid::occured($this->lendingId, $paidAmount));

        if ($paidAmount >= $amountBefore) {
            $this->recordThat(FineFullyPaid::occured($this->lendingId));
        }

        if ($paidAmount > $amountBefore) {
            $this->recordThat(FineOverpaid::occured($this->lendingId, $paidAmount - $amountBefore));
        }
    }

    public function accrue(float $amount): void
    {
        $this->throwIfNotDue();

        $this->recordThat(FineAccrued::occured($this->lendingId, $amount));
    }

    public function cancel(): void
    {
        $this->throwIfNotDue();

        $this->recordThat(FineCancelled::occured($this->lendingId));
    }

    public function lendingId(): LendingId
    {
        return $this->lendingId;
    }

    public function amount(): float
    {
        return $this->amount;
    }

    private function isDue(): bool
    {
        return $this->amount > 0;
    }

    private function throwIfNotDue(): void
    {
        if (!$this->isDue()) {
            throw new FineAlreadyPaid($this->lendingId);
        }
    }

    protected function aggregateId(): string
    {
        return $this->lendingId->toScalar();
    }

    protected function onFineIssued(FineIssued $event): void
    {
        $this->amount = $event->amount();
        $this->lendingId = $event->lendingId();
    }

    protected function onFinePaid(FinePaid $event): void
    {
        $this->amount -= $event->amount();
    }

    protected function onFineFullyPaid(FineFullyPaid $event): void
    {
        $this->amount = 0;
    }

    protected function onFineOverpaid(FineOverpaid $event): void
    {
        $this->amount = 0;
    }

    protected function onFineCancelled(FineCancelled $event): void
    {
        $this->amount = 0;
    }

    protected function onFineAccrued(FineAccrued $event): void
    {
        $this->amount += $event->amount();
    }
}
