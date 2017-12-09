<?php
declare(strict_types=1);

namespace Library\Application\Fine\Command\AcceptCancellationRequest;

use Library\Domain\Fine\CancellationRequest\FineCancellationRequests;

final class AcceptCancellationRequestHandler
{
    /** @var FineCancellationRequests */
    private $cancellationRequests;

    public function __construct(FineCancellationRequests $cancellationRequests)
    {
        $this->cancellationRequests = $cancellationRequests;
    }

    public function __invoke(AcceptCancellationRequest $command)
    {
        $request = $this->cancellationRequests->get($command->lendingId());
        $request->accept();
        $this->cancellationRequests->update($request);
    }
}
