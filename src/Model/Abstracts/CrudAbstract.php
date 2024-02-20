<?php

namespace Ipeweb\RecapSheets\Model\Abstracts;

abstract class CrudAbstract
{
    public function insert(array $params)
    {
        $erros = $this->validate($params);
        if (!empty($erros)) {
            return $erros;
        }

        return $this->prepare($params);
    }

    abstract public function validate(array $params);

    abstract public function prepare(array $params);
}
