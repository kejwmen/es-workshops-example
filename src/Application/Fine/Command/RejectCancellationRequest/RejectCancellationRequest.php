<?php
declare(strict_types=1);

namespace Library\Application\Fine\Command\RejectCancellationRequest;

use Library\Domain\LendingId;

final class RejectCancellationRequest
{
    /** @var LendingId */
    private $lendingId;

    public function __construct(LendingId $lendingId)
    {
        $this->lendingId = $lendingId;
    }

    public function lendingId(): LendingId
    {
        return $this->lendingId;
    }
}
