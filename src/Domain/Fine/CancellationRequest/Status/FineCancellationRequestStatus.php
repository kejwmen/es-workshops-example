<?php
declare(strict_types=1);

namespace Library\Domain\Fine\CancellationRequest\Status;

interface FineCancellationRequestStatus
{
    public const IDENTIFIER_ACCEPTED = 'accepted';
    public const IDENTIFIER_REJECTED = 'rejected';
    public const IDENTIFIER_SUBMITTED = 'submitted';

    public function isAccepted(): bool;
    public function isRejected(): bool;

    /**
     * @throws StatusAlreadyChanged
     */
    public function accept(): FineCancellationRequestStatus;

    /**
     * @throws StatusAlreadyChanged
     */
    public function reject(): FineCancellationRequestStatus;
}
