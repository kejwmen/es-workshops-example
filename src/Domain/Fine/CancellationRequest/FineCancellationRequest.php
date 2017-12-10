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

    /** @var string */
    private $reason;

    protected function __construct() {}

    public static function create(LendingId $lendingId, string $reason): self
    {
        $s = new self();
        $s->recordThat(FineCancellationRequestSubmitted::occured($lendingId, $reason));

        return $s;
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

    protected function onFineCancellationRequestSubmitted(FineCancellationRequestSubmitted $event)
    {
        $this->lendingId = $event->lendingId();
        $this->status = new SubmittedCancellationRequest();
        $this->reason = $event->reason();
    }

    protected function onFineCancellationRequestAccepted(FineCancellationRequestAccepted $event)
    {
        $this->lendingId = $event->lendingId();
        $this->status = $this->status->accept();
    }

    protected function onFineCancellationRequestRejected(FineCancellationRequestRejected $event)
    {
        $this->lendingId = $event->lendingId();
        $this->status = $this->status->reject();
    }
}
