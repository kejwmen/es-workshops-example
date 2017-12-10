<?php
declare(strict_types=1);

namespace Library\Domain\Fine;

use Library\Domain\LendingId;

final class FineAlreadyPaid extends \RuntimeException
{
    public function __construct(LendingId $lendingId)
    {
        parent::__construct(
            \sprintf(
                'Fine for lending %s is already paid',
                $lendingId->toScalar()
            )
        );
    }
}
