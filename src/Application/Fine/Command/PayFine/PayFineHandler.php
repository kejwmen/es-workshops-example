<?php
declare(strict_types=1);

namespace Library\Application\Fine\Command\PayFine;

use Library\Domain\Fine\Fine;
use Library\Domain\Fine\Fines;

final class PayFineHandler
{
    /** @var Fines */
    private $fines;

    public function __construct(Fines $fines)
    {
        $this->fines = $fines;
    }

    public function __invoke(PayFine $command)
    {
        /** @var Fine $fine */
        $fine = $this->fines->get($command->lendingId());

        $fine->pay($command->amount());

        $this->fines->change($fine);
    }
}
