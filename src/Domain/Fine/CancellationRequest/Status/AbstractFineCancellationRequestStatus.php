<?php
declare(strict_types=1);

namespace Library\Domain\Fine\CancellationRequest\Status;

abstract class AbstractFineCancellationRequestStatus implements FineCancellationRequestStatus
{
    public function isAccepted(): bool
    {
        return $this->identifier() === self::IDENTIFIER_ACCEPTED;
    }

    public function isRejected(): bool
    {
        return $this->identifier() === self::IDENTIFIER_REJECTED;
    }

    public function accept(): FineCancellationRequestStatus
    {
        throw new StatusAlreadyChanged(self::IDENTIFIER_ACCEPTED, $this->identifier());
    }

    public function reject(): FineCancellationRequestStatus
    {
        throw new StatusAlreadyChanged(self::IDENTIFIER_REJECTED, $this->identifier());
    }

    abstract protected function identifier();
}
