<?php

namespace Ipeweb\RecapSheets\Model\Abstracts;

abstract class CrudAbstract
{
    public function process(array $params)
    {
        $erros = $this->validate($params);
        if (!empty($erros)) {
            return $erros;
        }

        return $this->prepare($params);
    }

    public function insert(array $params)
    {
        return $this->process($params);
    }

    public function update(array $params)
    {
        return $this->process($params);
    }

    abstract public function validate(array $params);

    abstract public function prepare(array $params);
}
