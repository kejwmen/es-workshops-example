<?php
declare(strict_types=1);

namespace Library\Domain\Fine\CancellationRequest;

use Library\Domain\LendingId;

interface FineCancellationRequests
{
    public function get(LendingId $lendingId): FineCancellationRequest;

    public function submit(FineCancellationRequest $request): void;
    public function update(FineCancellationRequest $request): void;
}
