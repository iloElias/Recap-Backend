<?php

namespace Ipeweb\RecapSheets\Model\Abstracts;

abstract class CrudAbstract
{
    public function process(array $params, string $args = null)
    {
        $erros = $this->validate($params, $args);
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

    public function delete(array $params)
    {
        return $this->process($params);
    }

    abstract public function validate(array $params, string $args = null);

    abstract public function prepare(array $params);
}
