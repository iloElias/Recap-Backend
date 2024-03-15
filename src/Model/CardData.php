<?php

namespace Ipeweb\RecapSheets\Model;

use Ipeweb\RecapSheets\Model\ModelHandler;

class CardData
{
    protected string $table = 'cards';

    protected array $fields = ['id', 'theme_id', 'last_change', 'synopsis', "imd", 'color'];

    private ModelHandler $modelHandler;

    public function __construct()
    {
        $this->modelHandler = ModelHandler::getModelHandlerInstance($this->table, $this->fields);
    }

    public function insert(array $data): array
    {
        return $this->modelHandler->insert($data);
    }

    public function get(array $data): array
    {
        return $this->modelHandler->get($data);
    }

    public function getSearch(array $data, int $offset = 0, int $limit = 25, array $order = null, $strict = false): array
    {
        return $this->modelHandler->getSearch($data, $offset, $limit, $order, $strict);
    }

    public function getAll(int $offset = 0, int $limit = 25, array $order = null): array
    {
        return $this->modelHandler->getAll($offset, $limit, $order);
    }

    public function update(int $id, array $data)
    {
        return $this->modelHandler->update($id, $data);
    }

    public function inactive(int $id)
    {
        return $this->modelHandler->inactive($id);
    }
}
