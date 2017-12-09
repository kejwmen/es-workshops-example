<?php
declare(strict_types=1);

namespace Library\Domain\Fine;

use Library\Domain\AggregateRoot;
use Library\Domain\LendingId;

class Fine extends AggregateRoot
{
    /** @var LendingId */
    private $lendingId;

    /** @var float */
    private $amount;

    protected function __construct(LendingId $lendingId, float $amount)
    {
        $this->recordThat(FineIssued::occured($lendingId, $amount));
    }

    public static function create(LendingId $lendingId, float $amount): self
    {
        return new self($lendingId, $amount);
    }

    public function pay(float $paidAmount): void
    {
        $this->recordThat(FinePaid::occured($this->lendingId, $paidAmount));

        if ($paidAmount >= $this->amount) {
            $this->recordThat(FineFullyPaid::occured($this->lendingId));

            if ($paidAmount > $this->amount) {
                $this->recordThat(FineOverpaid::occured($this->lendingId, $paidAmount - $this->amount));
            }
        }
    }

    public function cancel(): void
    {
        $this->recordThat(FineCancelled::occured($this->lendingId));
    }

    protected function aggregateId(): string
    {
        return $this->lendingId->toScalar();
    }

    private function onFineIssued(FineIssued $event): void
    {
        $this->amount = $event->payload()['amount'];
        $this->lendingId = $event->payload()['lendingId'];
    }

    private function onFinePaid(FinePaid $event): void
    {
        $this->amount -= $event->amount();
    }

    private function onFineFullyPaid(FineFullyPaid $event): void
    {
        $this->amount = 0;
    }

    private function onFineOverpaid(FineOverpaid $event): void
    {
        $this->amount = 0;
    }

    private function onFineCancelled(FineCancelled $event): void
    {
        $this->amount = 0;
    }
}
