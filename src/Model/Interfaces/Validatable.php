<?php

namespace Ipeweb\IpeSheets\Model\Interfaces;

interface Validatable
{
    public function validate(callable $callable): mixed;
}
