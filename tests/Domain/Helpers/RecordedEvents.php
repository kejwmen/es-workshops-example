<?php
declare(strict_types=1);

namespace Tests\Library\Domain\Helpers;

use Assert\Assertion;
use function Functional\select;
use Prooph\EventSourcing\AggregateChanged;

final class RecordedEvents implements \Countable
{
    /** @var array */
    private $events;

    public function __construct(array $events)
    {
        $this->events = $events;
    }

    public function all(): array
    {
        return $this->events;
    }

    public function count()
    {
        return \count($this->events);
    }

    public function ofClass(string $className): array
    {
        return \array_values(select($this->events, function (AggregateChanged $event) use ($className) {
            return $event instanceof $className;
        }));
    }

    public function oneOfClass(string $className): AggregateChanged
    {
        $all = $this->ofClass($className);
        Assertion::count($all, 1);

        return $all[0];
    }
}
