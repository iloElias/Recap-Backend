<?php

namespace Ipeweb\IpeSheets\Model\Interfaces;

use PHPUnit\Framework\MockObject\Rule\Parameters;

interface Serializable
{
    public function toJson(mixed $parameters): string;
    public function toString(mixed $parameters): string;
}
