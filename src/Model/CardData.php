<?php

namespace Ipeweb\IpeSheets\Model;

use Ipeweb\IpeSheets\Model\ModelHandler;
use Ipeweb\IpeSheets\Model\Interfaces\CrudInterface;

class CardData implements CrudInterface
{
    protected string $table = 'cards';
    protected array $fields = ['id', 'theme_id', 'last_change', 'synopsis', "imd"];
    private ModelHandler $dataHandler;

    public function __construct()
    {
        $this->dataHandler = ModelHandler::getModelHandlerInstance($this->table, $this->fields);
    }

    public function insert(array $data): int
    {
        return $this->dataHandler->insert($data);
    }

    public function get(string $key, $value): array
    {
        return $this->dataHandler->get([$key => $value]);
    }

    public function getSearch(array $data, int $offset = 1, int $limit = 25, array $order = null): array
    {
        return $this->dataHandler->getSearch($data, $offset, $limit, $order);
    }

    public function getAll(int $offset = 1, int $limit = 25, array $order = null): array
    {
        return $this->dataHandler->getAll($offset, $limit, $order);
    }

    public function update(int $id, array $data)
    {
        return $this->dataHandler->update($id, $data);
    }
    public function inactive(int $id)
    {
        return $this->dataHandler->inactive($id);
    }
}
