<?php
declare(strict_types=1);

namespace Library\Domain\Fine\CancellationRequest\Status;

final class RejectedCancellationRequest extends AbstractFineCancellationRequestStatus
{
    protected function identifier()
    {
        return self::IDENTIFIER_REJECTED;
    }
}
