<?php
declare(strict_types=1);

namespace Tests\Library\Domain\Helpers;

use Library\Domain\AggregateRoot;

final class TestAggregate extends AggregateRoot
{
    /** @var bool */
    private $called;

    public static function create(): self
    {
        $s = new self();
        $s->called = false;
        return $s;
    }

    protected function aggregateId(): string
    {
        return uniqid("foo", true);
    }

    public function triggerTestEvent()
    {
        $this->recordThat(TestEvent::occur($this->aggregateId()));
    }

    public function triggerAnotherTestEvent()
    {
        $this->recordThat(AnotherTestEvent::occur($this->aggregateId()));
    }

    protected function onTestEvent(TestEvent $event)
    {
        $this->called = true;
    }

    public function wasCalled(): bool
    {
        return $this->called;
    }
}
