<?php
declare(strict_types=1);

namespace Library\Application\Fine\CancellationRequest\Command\SubmitCancellationRequest;

use Library\Domain\Fine\CancellationRequest\FineCancellationRequest;
use Library\Domain\Fine\CancellationRequest\FineCancellationRequests;

final class SubmitCancellationRequestHandler
{
    /** @var FineCancellationRequests */
    private $cancellationRequests;

    public function __construct(FineCancellationRequests $cancellationRequests)
    {
        $this->cancellationRequests = $cancellationRequests;
    }

    public function __invoke(SubmitCancellationRequest $command)
    {
        $request = FineCancellationRequest::create($command->lendingId(), $command->reason());
        $this->cancellationRequests->submit($request);
    }
}
