<?php
declare(strict_types=1);

namespace Library\Domain\Fine\CancellationRequest\Status;

final class SubmittedCancellationRequest extends AbstractFineCancellationRequestStatus
{
    protected function identifier()
    {
        return self::IDENTIFIER_SUBMITTED;
    }

    public function accept(): FineCancellationRequestStatus
    {
        return new AcceptedCancellationRequest();
    }

    public function reject(): FineCancellationRequestStatus
    {
        return new RejectedCancellationRequest();
    }
}
