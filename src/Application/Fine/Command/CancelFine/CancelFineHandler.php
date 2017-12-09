<?php
declare(strict_types=1);

namespace Library\Application\Fine\Command\CancelFine;

use Library\Domain\Fine\Fine;
use Library\Domain\Fine\Fines;

final class CancelFineHandler
{
    /** @var Fines */
    private $fines;

    public function __construct(Fines $fines)
    {
        $this->fines = $fines;
    }

    public function __invoke(CancelFine $command)
    {
        /** @var Fine $fine */
        $fine = $this->fines->get($command->lendingId());
        $fine->cancel();
        $this->fines->change($fine);
    }
}
