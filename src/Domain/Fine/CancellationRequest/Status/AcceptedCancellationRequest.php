<?php
declare(strict_types=1);

namespace Library\Domain\Fine\CancellationRequest\Status;

final class AcceptedCancellationRequest extends AbstractFineCancellationRequestStatus
{
    protected function identifier()
    {
        return self::IDENTIFIER_ACCEPTED;
    }
}
