<?php
declare(strict_types=1);

namespace Library\Application\Fine\CancellationRequest;

use Library\Domain\Fine\CancellationRequest\FineCancellationRequestAccepted;
use Library\Domain\Fine\CancellationRequest\FineCancellationRequestRejected;

final class FineCancellationRequestProcessManager
{
    public function onFineCancellationRequestAccepted(FineCancellationRequestAccepted $event)
    {
        // TODO: Notify user
    }

    public function onFineCancellationRequestRejected(FineCancellationRequestRejected $event)
    {
        // TODO: Notify user
    }
}
