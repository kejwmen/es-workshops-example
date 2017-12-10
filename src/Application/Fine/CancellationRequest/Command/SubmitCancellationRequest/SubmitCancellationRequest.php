<?php
declare(strict_types=1);

namespace Library\Application\Fine\CancellationRequest\Command\SubmitCancellationRequest;

use Library\Domain\LendingId;

final class SubmitCancellationRequest
{
    /** @var LendingId */
    private $lendingId;
    /** @var string */
    private $reason;

    public function __construct(LendingId $lendingId, string $reason)
    {
        $this->lendingId = $lendingId;
        $this->reason = $reason;
    }

    public function lendingId(): LendingId
    {
        return $this->lendingId;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
