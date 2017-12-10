<?php
declare(strict_types=1);

namespace Tests\Library\Domain\Fine;

use Library\Domain\Fine\Fine;
use Library\Domain\Fine\FineAccrued;
use Library\Domain\Fine\FineAlreadyPaid;
use Library\Domain\Fine\FineCancelled;
use Library\Domain\Fine\FineFullyPaid;
use Library\Domain\Fine\FineIssued;
use Library\Domain\Fine\FineOverpaid;
use Library\Domain\Fine\FinePaid;
use Library\Domain\LendingId;
use Tests\Library\Domain\AggregateTestCase;

/**
 * @group unit
 * @group domain
 * @group fine
 */
final class FineTest extends AggregateTestCase
{
    /** @var null|LendingId */
    private $lendingId;

    /** @var float */
    private $initialAmount = 3.14;

    public function setUp()
    {
        parent::setUp();

        $this->lendingId = new LendingId();
    }

    /**
     * @test
     */
    public function shouldIssueAFineFromLendingAndAmount()
    {
        // given
        $lendingId = $this->lendingId;
        $amount = $this->initialAmount;

        // when
        $fine = Fine::issue($lendingId, $amount);
        $events = $this->popRecordedEvents($fine);
        /** @var FineIssued $issuedEvent */
        $issuedEvent = $events->oneOfClass(FineIssued::class);

        // then
        self::assertTrue($lendingId->equals($issuedEvent->lendingId()));
        self::assertSame($amount, $issuedEvent->amount());
        self::assertTrue($lendingId->equals($fine->lendingId()));
    }

    /**
     * @test
     */
    public function shouldPayFine()
    {
        // given
        $fine = $this->reconstitute([
            $this->fineIssued()
        ]);

        $payAmount = 2.0;

        // when
        $fine->pay($payAmount);
        $events = $this->popRecordedEvents($fine);

        // then
        self::assertCount(1, $events);
        /** @var FinePaid $paidEvent */
        $paidEvent = $events->oneOfClass(FinePaid::class);
        self::assertSame($payAmount, $paidEvent->amount());
        self::assertTrue($this->lendingId->equals($paidEvent->lendingId()));
    }

    /**
     * @test
     */
    public function shouldFullyPayFine()
    {
        // given
        $fine = $this->reconstitute([
            $this->fineIssued()
        ]);

        // when
        $fine->pay($this->initialAmount);
        $events = $this->popRecordedEvents($fine);

        // then
        self::assertCount(2, $events);
        /** @var FinePaid $paidEvent */
        $paidEvent = $events->oneOfClass(FinePaid::class);
        self::assertSame($this->initialAmount, $paidEvent->amount());

        /** @var FineFullyPaid $fullyPaidEvent */
        $fullyPaidEvent = $events->oneOfClass(FineFullyPaid::class);
        self::assertTrue($this->lendingId->equals($fullyPaidEvent->lendingId()));
    }

    /**
     * @test
     */
    public function shouldOverpayFine()
    {
        // given
        $fine = $this->reconstitute([
            $this->fineIssued()
        ]);

        $overpay = 1.0;

        // when
        $fine->pay($this->initialAmount + $overpay);
        $events = $this->popRecordedEvents($fine);

        // then
        self::assertCount(3, $events);
        /** @var FinePaid $paidEvent */
        $paidEvent = $events->oneOfClass(FinePaid::class);
        self::assertSame($this->initialAmount + $overpay, $paidEvent->amount());

        /** @var FineFullyPaid $fullyPaidEvent */
        $fullyPaidEvent = $events->oneOfClass(FineFullyPaid::class);
        self::assertTrue($this->lendingId->equals($fullyPaidEvent->lendingId()));

        /** @var FineOverpaid $overpaidEvent */
        $overpaidEvent = $events->oneOfClass(FineOverpaid::class);
        self::assertSame($overpay, $overpaidEvent->overpayAmount());
        self::assertTrue($this->lendingId->equals($overpaidEvent->lendingId()));
    }

    /**
     * @test
     */
    public function shouldCancelFine()
    {
        // given
        $fine = $this->reconstitute([
            $this->fineIssued()
        ]);

        // when
        $fine->cancel();
        $events = $this->popRecordedEvents($fine);

        // then
        self::assertCount(1, $events);
        /** @var FineCancelled $cancelledEvent */
        $cancelledEvent = $events->oneOfClass(FineCancelled::class);
        self::assertTrue($this->lendingId->equals($cancelledEvent->lendingId()));
    }

    /**
     * @test
     */
    public function shouldAccrueFine()
    {
        // given
        $fine = $this->reconstitute([
            $this->fineIssued()
        ]);

        $increaseAmount = 2.0;

        // when
        $fine->accrue($increaseAmount);
        $events = $this->popRecordedEvents($fine);

        // then
        self::assertCount(1, $events);
        /** @var FineAccrued $accruedEvent */
        $accruedEvent = $events->oneOfClass(FineAccrued::class);
        self::assertSame($increaseAmount, $accruedEvent->amount());
        self::assertSame($this->initialAmount + $increaseAmount, $fine->amount());
        self::assertTrue($this->lendingId->equals($accruedEvent->lendingId()));
    }

    /**
     * @test
     */
    public function shouldThrowWhenAccruedFineIsAlreadyPaid()
    {
        // given
        $fine = $this->reconstitute([
            $this->fineIssued(),
            $this->fineFullyPaid()
        ]);

        // expect
        self::expectException(FineAlreadyPaid::class);

        // when
        $fine->accrue(1);
    }

    /**
     * @test
     */
    public function shouldThrowWhenPayedFineIsAlreadyPaid()
    {
        // given
        $fine = $this->reconstitute([
            $this->fineIssued(),
            $this->fineFullyPaid()
        ]);

        // expect
        self::expectException(FineAlreadyPaid::class);

        // when
        $fine->pay(1);
    }

    private function reconstitute(array $events): Fine
    {
        return $this->reconstituteAggregateFromHistory(Fine::class, $events);
    }

    private function fineIssued(): FineIssued
    {
        return FineIssued::occured($this->lendingId, $this->initialAmount);
    }

    private function fineFullyPaid(): FineFullyPaid
    {
        return FineFullyPaid::occured($this->lendingId);
    }
}
