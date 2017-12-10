<?php
declare(strict_types=1);

namespace Library\Application\Fine\CancellationRequest\Command\RejectCancellationRequest;

use Library\Domain\Fine\CancellationRequest\FineCancellationRequests;

final class RejectCancellationRequestHandler
{
    /** @var FineCancellationRequests */
    private $cancellationRequests;

    public function __construct(FineCancellationRequests $cancellationRequests)
    {
        $this->cancellationRequests = $cancellationRequests;
    }

    public function __invoke(RejectCancellationRequest $command)
    {
        $request = $this->cancellationRequests->get($command->lendingId());
        $request->reject();
        $this->cancellationRequests->update($request);
    }
}
