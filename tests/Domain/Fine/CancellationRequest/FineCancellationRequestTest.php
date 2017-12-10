<?php
declare(strict_types=1);

namespace Tests\Library\Domain\Fine;

use Library\Domain\Fine\CancellationRequest\FineCancellationRequest;
use Library\Domain\Fine\CancellationRequest\FineCancellationRequestAccepted;
use Library\Domain\Fine\CancellationRequest\FineCancellationRequestRejected;
use Library\Domain\Fine\CancellationRequest\FineCancellationRequestSubmitted;
use Library\Domain\LendingId;
use Tests\Library\Domain\AggregateTestCase;

/**
 * @group unit
 * @group domain
 * @group fine-cancellation-request
 */
final class FineCancellationRequestTest extends AggregateTestCase
{
    /** @var null|LendingId */
    private $lendingId;

    /** @var string */
    private $reason;

    public function setUp()
    {
        parent::setUp();

        $this->lendingId = new LendingId();
        $this->reason = 'Beacuse I can';
    }

    /**
     * @test
     */
    public function shouldSubmitRequestFromLendingAndReason()
    {
        // given
        $lendingId = $this->lendingId;
        $reason = $this->reason;

        // when
        $request = FineCancellationRequest::create($lendingId, $reason);
        $events = $this->popRecordedEvents($request);
        /** @var FineCancellationRequestSubmitted $submittedEvent */
        $submittedEvent = $events->oneOfClass(FineCancellationRequestSubmitted::class);

        // then
        self::assertTrue($lendingId->equals($submittedEvent->lendingId()));
        self::assertSame($reason, $submittedEvent->reason());
    }

    /**
     * @test
     */
    public function shouldAccept()
    {
        // given
        $request = $this->reconstitute([
            $this->requestSubmitted()
        ]);

        // when
        $request->accept();
        $events = $this->popRecordedEvents($request);

        // then
        self::assertCount(1, $events);
        /** @var FineCancellationRequestAccepted $acceptedEvent */
        $acceptedEvent = $events->oneOfClass(FineCancellationRequestAccepted::class);
        self::assertTrue($this->lendingId->equals($acceptedEvent->lendingId()));
    }

    /**
     * @test
     */
    public function shouldReject()
    {
        // given
        $request = $this->reconstitute([
            $this->requestSubmitted()
        ]);

        // when
        $request->reject();
        $events = $this->popRecordedEvents($request);

        // then
        self::assertCount(1, $events);
        /** @var FineCancellationRequestRejected $rejectedEvent */
        $rejectedEvent = $events->oneOfClass(FineCancellationRequestRejected::class);
        self::assertTrue($this->lendingId->equals($rejectedEvent->lendingId()));
    }

    private function reconstitute(array $events): FineCancellationRequest
    {
        return $this->reconstituteAggregateFromHistory(FineCancellationRequest::class, $events);
    }

    private function requestSubmitted(): FineCancellationRequestSubmitted
    {
        return FineCancellationRequestSubmitted::occured($this->lendingId, $this->reason);
    }
}
