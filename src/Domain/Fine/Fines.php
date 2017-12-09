<?php
declare(strict_types=1);

namespace Library\Domain\Fine;

use Library\Domain\LendingId;

interface Fines
{
    public function issue(Fine $fine): void;
    public function change(Fine $fine): void;

    /**
     * @throws FineNotFound
     */
    public function get(LendingId $lendingId): Fine;
}
