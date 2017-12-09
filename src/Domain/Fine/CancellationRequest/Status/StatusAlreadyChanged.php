<?php
declare(strict_types=1);

namespace Library\Domain\Fine\CancellationRequest\Status;

final class StatusAlreadyChanged extends \RuntimeException
{
    public function __construct(string $targetState, string $currentState)
    {
        parent::__construct(
            \sprintf(
                'Cannot %s request because is already %sed',
                $targetState,
                $currentState
            )
        );
    }
}
