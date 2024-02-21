<?php

namespace Ipeweb\RecapSheets\Model;

use Ipeweb\RecapSheets\Model\ModelHandler;

class ProjectData
{
    protected string $table = 'projects';
    protected array $fields = ['id', 'name', 'card_id', 'type', 'is_active'];
    private ModelHandler $dataHandler;

    public function __construct()
    {
        $this->dataHandler = ModelHandler::getModelHandlerInstance($this->table, $this->fields);
    }

    public function insert(array $data): array
    {
        return $this->dataHandler->insert($data);
    }

    public function get(array $data): array
    {
        return $this->dataHandler->get($data);
    }

    public function getSearch(array $data, int $offset = 0, int $limit = 25, array $order = null, $strict = false): array
    {
        return $this->dataHandler->getSearch($data, $offset, $limit, $order, $strict);
    }

    public function getAll(int $offset = 0, int $limit = 25, array $order = null): array
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
