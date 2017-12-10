<?php
declare(strict_types=1);

namespace Library\Application\Fine;

use Library\Application\Fine\Command\CancelFine\CancelFine;
use Library\Domain\Fine\CancellationRequest\FineCancellationRequestAccepted;
use Library\Domain\Fine\FineOverpaid;
use Prooph\ServiceBus\CommandBus;

final class FineProcessManager
{
    /** @var CommandBus */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function onFineCancellationRequestAccepted(FineCancellationRequestAccepted $event)
    {
        $this->commandBus->dispatch(new CancelFine($event->lendingId()));
    }

    public function onFineOverpaid(FineOverpaid $event)
    {
        // TODO: Return money
    }
}
