<?php

namespace Ipeweb\RecapSheets\Model;

use Ipeweb\RecapSheets\Database\SQLDatabase;
use Ipeweb\RecapSheets\Model\ModelHandler;
use Ipeweb\RecapSheets\Model\Interfaces\CrudInterface;

class UserProjectsData implements CrudInterface
{
    protected string $table = 'project_users';
    protected array $fields = ['user_id', 'project_id', 'user_permissions'];
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
        $key = null;
        $value = null;
        foreach ($data as $arrKey => $arrValue) {
            $key = $arrKey;
            $value = $arrValue;
        }


        if ($key == "user_id" && is_numeric($value)) {
            $sqlDatabase = new SQLDatabase();
            $sqlDatabase->setQuery(
                "SELECT p.id, p.name, c.synopsis FROM projects p
                JOIN project_users up ON p.id = up.project_id
                JOIN cards c ON c.id = p.card_id
                WHERE up.user_id = {$value}
                AND p.type = 'card'
                AND p.state = 'active'
                AND up.user_permissions = 'own';"
            );

            $result = $sqlDatabase->execute();

            return $result;
        }

        return [];
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
