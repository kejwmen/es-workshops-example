<?php
declare(strict_types=1);

namespace Tests\Library\Domain\Fine\CancellationRequest\Status;

use Library\Domain\Fine\CancellationRequest\Status\AcceptedCancellationRequest;
use Library\Domain\Fine\CancellationRequest\Status\RejectedCancellationRequest;
use Library\Domain\Fine\CancellationRequest\Status\SubmittedCancellationRequest;
use PHPUnit\Framework\TestCase;

final class SubmittedCancellationRequestTest extends TestCase
{
    /**
     * @test
     */
    public function shouldBeAccepted()
    {
        // given
        $status = new SubmittedCancellationRequest();

        // when
        $nextStatus = $status->accept();

        // then
        self::assertInstanceOf(AcceptedCancellationRequest::class, $nextStatus);
        self::assertTrue($nextStatus->isAccepted());
        self::assertFalse($nextStatus->isRejected());
    }

    /**
     * @test
     */
    public function shouldBeRejected()
    {
        // given
        $status = new SubmittedCancellationRequest();

        // when
        $nextStatus = $status->reject();

        // then
        self::assertInstanceOf(RejectedCancellationRequest::class, $nextStatus);
        self::assertTrue($nextStatus->isRejected());
        self::assertFalse($nextStatus->isAccepted());
    }
}
