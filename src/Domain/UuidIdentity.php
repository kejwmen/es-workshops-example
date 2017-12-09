<?php
declare(strict_types=1);

namespace Library\Domain;

use Ramsey\Uuid\Uuid;

class UuidIdentity implements Identity
{
    /** @var \Ramsey\Uuid\UuidInterface */
    private $uuid;

    public function __construct(?string $uuidString = null)
    {
        if ($uuidString) {
            $this->uuid = Uuid::fromString($uuidString);
        } else {
            $this->uuid = Uuid::uuid4();
        }
    }

    public function equals(Identity $identity): bool
    {
        return $identity instanceof static
            && $this->uuid->equals($identity->uuid);
    }

    public function toScalar()
    {
        return $this->uuid->toString();
    }

    public function __toString()
    {
        return (string) $this->toScalar();
    }
}
