<?php

namespace Library\Domain;

interface Identity
{
    public function equals(Identity $identity): bool;
    public function toScalar();
    public function __toString();
}