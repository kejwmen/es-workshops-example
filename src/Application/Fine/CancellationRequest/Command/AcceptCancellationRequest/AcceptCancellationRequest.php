<?php
declare(strict_types=1);

namespace Library\Application\Fine\CancellationRequest\Command\AcceptCancellationRequest;

use Library\Domain\LendingId;

final class AcceptCancellationRequest
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
