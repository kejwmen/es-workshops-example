<?php
declare(strict_types=1);

namespace Tests\Library\Domain\Fine\CancellationRequest\Status;

use Library\Domain\Fine\CancellationRequest\Status\RejectedCancellationRequest;
use Library\Domain\Fine\CancellationRequest\Status\StatusAlreadyChanged;
use PHPUnit\Framework\TestCase;

final class RejectedCancellationRequestTest extends TestCase
{
    /**
     * @test
     */
    public function shouldThrowWhenAccepted()
    {
        // given
        $status = new RejectedCancellationRequest();

        // expect
        self::expectException(StatusAlreadyChanged::class);

        // when
        $status->accept();
    }

    /**
     * @test
     */
    public function shouldThrowWhenRejected()
    {
        // given
        $status = new RejectedCancellationRequest();

        // expect
        self::expectException(StatusAlreadyChanged::class);

        // when
        $status->reject();
    }
}
