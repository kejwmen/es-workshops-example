<?php
declare(strict_types=1);

namespace Tests\Library\Domain;

use PHPUnit\Framework\TestCase;
use Prooph\EventSourcing\Aggregate\AggregateType;
use Prooph\EventSourcing\AggregateRoot;
use Prooph\EventSourcing\EventStoreIntegration\AggregateTranslator;
use Tests\Library\Domain\Helpers\RecordedEvents;

abstract class AggregateTestCase extends TestCase
{
    /**
     * @var AggregateTranslator
     */
    private $aggregateTranslator;

    protected function popRecordedEvents(AggregateRoot $aggregateRoot): RecordedEvents
    {
        return new RecordedEvents($this->getAggregateTranslator()->extractPendingStreamEvents($aggregateRoot));
    }

    protected function reconstituteAggregateFromHistory(string $aggregateRootClass, array $events): AggregateRoot
    {
        return $this->getAggregateTranslator()->reconstituteAggregateFromHistory(
            AggregateType::fromAggregateRootClass($aggregateRootClass),
            new \ArrayIterator($events)
        );
    }

    private function getAggregateTranslator(): AggregateTranslator
    {
        if (null === $this->aggregateTranslator) {
            $this->aggregateTranslator = new AggregateTranslator();
        }

        return $this->aggregateTranslator;
    }
}
