<?php
declare(strict_types=1);

namespace Tests\Library\Domain;

use Library\Domain\Identity;
use Library\Domain\UuidIdentity;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

/**
 * @group unit
 * @group domain
 * @group identity
 */
final class UuidIdentityTest extends TestCase
{
    /**
     * @test
     */
    public function shouldGenerateNewUuid()
    {
        // when
        $uuidIdentity = new UuidIdentity();

        // then
        self::assertTrue(Uuid::isValid($uuidIdentity));
    }

    /**
     * @test
     */
    public function shouldAcceptGivenValidUuid()
    {
        // given
        $uuidString = Uuid::uuid4()->toString();

        // when
        $uuidIdentity = new UuidIdentity($uuidString);

        // then
        self::assertSame($uuidString, $uuidIdentity->__toString());
    }

    /**
     * @test
     */
    public function shouldCompareWithOtherIdentityAndReturnFalse()
    {
        // given
        $otherIdentity = new class () implements Identity {
            public function equals(Identity $identity): bool { return false; }
            public function toScalar() { return 'foo'; }
            public function __toString() { return $this->toScalar(); }
        };

        $uuidIdentity = new UuidIdentity();

        // when
        $result = $uuidIdentity->equals($otherIdentity);

        // then
        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldCompareWithDifferentUuidIdentityAndReturnFalse()
    {
        // given
        $uuidIdentity = new UuidIdentity();

        $otherUuidIdentity = new UuidIdentity();

        // when
        $result = $uuidIdentity->equals($otherUuidIdentity);

        // then
        self::assertFalse($result);
    }

    /**
     * @test
     */
    public function shouldCompareWithEqualUuidIdentityAndReturnTrue()
    {
        // given
        $uuidString = Uuid::uuid4()->toString();

        $uuidIdentity = new UuidIdentity($uuidString);

        $otherUuidIdentity = new UuidIdentity($uuidString);

        // when
        $result = $uuidIdentity->equals($otherUuidIdentity);

        // then
        self::assertTrue($result);
    }
}
