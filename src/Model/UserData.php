<?php

namespace Ipeweb\RecapSheets\Model;

use Ipeweb\RecapSheets\Model\ModelHandler;
use Ipeweb\RecapSheets\Model\Interfaces\CrudInterface;

class UserData implements CrudInterface
{
    protected string $table = 'users';

    protected array $fields = ['id', 'google_id', 'name', 'username', 'email', "picture_path", "preferred_lang", 'logged_in'];

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

    public function getSearch(array $data, int $offset = 0, int $limit = 25, array $order = null, $strict = false, $conditional = "AND"): array
    {
        if (isset($data['picture_path'])) {
            unset($data['picture_path']);
        }

        return $this->modelHandler->getSearch($data, $offset, $limit, $order, $strict, $conditional);
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