<?php
declare(strict_types=1);

namespace Library\Domain\Fine\CancellationRequest;

use Library\Domain\AggregateRoot;
use Library\Domain\Fine\CancellationRequest\Status\FineCancellationRequestStatus;
use Library\Domain\Fine\CancellationRequest\Status\SubmittedCancellationRequest;
use Library\Domain\LendingId;

final class FineCancellationRequest extends AggregateRoot
{
    /** @var LendingId */
    private $lendingId;

    /** @var FineCancellationRequestStatus */
    private $status;

    protected function __construct(LendingId $lendingId)
    {
        $this->recordThat(FineCancellationRequestSubmitted::occured($lendingId));
    }

    public static function create(LendingId $lendingId): self
    {
        return new self($lendingId);
    }

    public function accept()
    {
        $this->recordThat(FineCancellationRequestAccepted::occured($this->lendingId));
    }

    public function reject()
    {
        $this->recordThat(FineCancellationRequestRejected::occured($this->lendingId));
    }

    protected function aggregateId(): string
    {
        return $this->lendingId->toScalar();
    }

    private function onFineCancellationRequestSubmitted(FineCancellationRequestSubmitted $event)
    {
        $this->lendingId = $event->lendingId();
        $this->status = new SubmittedCancellationRequest();
    }

    private function onFineCancellationRequestAccepted(FineCancellationRequestSubmitted $event)
    {
        $this->lendingId = $event->lendingId();
        $this->status->accept();
    }

    private function onFineCancellationRequestRejected(FineCancellationRequestSubmitted $event)
    {
        $this->lendingId = $event->lendingId();
        $this->status->reject();
    }
}
