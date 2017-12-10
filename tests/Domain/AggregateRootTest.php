<?php
declare(strict_types=1);

namespace Tests\Library\Domain;

use PHPUnit\Framework\TestCase;
use Tests\Library\Domain\Helpers\TestAggregate;

/**
 * @group unit
 * @group domain
 * @group aggregate
 */
final class AggregateRootTest extends TestCase
{
    /**
     * @test
     */
    public function shouldApplyEventOnValidMethod()
    {
        // given
        $aggregate = TestAggregate::create();

        // when
        $aggregate->triggerTestEvent();

        // then
        self::assertTrue($aggregate->wasCalled());
    }

    /**
     * @test
     */
    public function shouldApplyEventOnNotExistingMethodAndFail()
    {
        // given
        $aggregate = TestAggregate::create();

        // expect
        self::expectException(\RuntimeException::class);

        // when
        $aggregate->triggerAnotherTestEvent();
    }
}
