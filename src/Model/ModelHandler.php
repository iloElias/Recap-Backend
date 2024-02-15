<?php

namespace Ipeweb\IpeSheets\Model;

use InvalidArgumentException;
use Ipeweb\IpeSheets\Database\SQLDatabase;
use Ipeweb\IpeSheets\Exceptions\NotNecessaryDataException;
use Ipeweb\IpeSheets\Model\Interfaces\CrudInterface;
use Ipeweb\IpeSheets\Services\Utils;

class ModelHandler implements CrudInterface
{
    private static ?ModelHandler $modelHandlerInstance = null;
    protected string $table = '';
    protected array $switchableTables = ['users', 'projects'];
    protected array $fields = [];

    public static function getModelHandlerInstance(string $table, array $fields = []): ModelHandler
    {
        if (self::$modelHandlerInstance === null) {
            self::$modelHandlerInstance = new ModelHandler();
        }
        self::$modelHandlerInstance->table = $table;
        self::$modelHandlerInstance->fields = $fields;

        return self::$modelHandlerInstance;
    }

    private function __construct()
    {
    }

    public function insert(array $data): array
    {
        foreach ($data as $key => $value) {
            if (!Utils::arrayFind($this->fields, $key)) {
                throw new InvalidArgumentException("Invalid value was sent to use .Can't use ['{$key}' => '{$value}'] in this insert");
            }
        }

        $database = new SQLDatabase();
        $database->insert($this->table, $data)
            ->bindParams();

        try {
            $result = $database->execute();

            return $result;
        } catch (\Throwable $e) {
            echo json_encode(
                [
                    "message" => "An error ocurred, the insert was not executed or did not returned the id",
                    "error" => $e->getMessage() . " " . $e->getFile() . " " . $e->getLine()
                ]
            );
            return [];
        }
    }

    public function get(array $data): array
    {
        foreach ($data as $key => $value) {
            if (!Utils::arrayFind($this->fields, $key)) {
                throw new NotNecessaryDataException("The key '{$key}' was not found in valid fields array");
            }
        }

        $database = new SQLDatabase();
        $database->select($this->table)
            ->where($data)
            ->limit(1)
            ->bindParams();

        try {
            $result = $database->execute();

            if (!$result) {
                return [];
            }

            return $result;
        } catch (\Throwable $e) {
            echo $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();
            return [];
        }
    }

    public function getSearch(array $data, int $offset = 0, int $limit = 25, array $order = null, $strict = false): array
    {
        foreach ($data as $key => $value) {
            if ($key === null || $key === "" || $value === null || $value === "") {
                throw new InvalidArgumentException("Some of the received data are invalid or blank");
            }
        }

        $database = new SQLDatabase();
        $database->select($this->table, "*")
            ->where($data, strict: $strict);

        if (array_search('visible', $this->fields) !== false) {
            $database->where(["visible" => 'true']);
            var_dump($this->fields);
        }

        $database->limit($limit)
            ->offset($offset)
            ->bindParams();

        if (isset($order) && isset($order['field'])) {
            $database->orderBy(
                $order['field'],
                strtoupper($order["direction"] ?? "ASC")
            );
        }

        try {
            $result = $database->execute();

            return $result ?? [];
        } catch (\Throwable $e) {
            echo $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();
            return [];
        }
    }

    public function getAll(int $offset = 0, int $limit = 25, array $order = null): array
    {
        $database = new SQLDatabase();
        $database->select($this->table);

        if (array_search('visible', $this->fields) !== false) {
            $database->where(["visible" => 'true']);
            var_dump($this->fields);
        }
        if (isset($order) && isset($order['field'])) {
            $database->orderBy($order['field'], isset($order["direction"]) ? $order["direction"] : "ASC");
        }

        $database->limit($limit)
            ->offset($offset)
            ->bindParams();

        try {
            $result = $database->execute();

            if (!$result) {
                return [];
            }

            return $result;
        } catch (\Throwable $e) {
            echo $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();
            return [];
        }
    }

    public function update(int $id, array $data)
    {
        $database = new SQLDatabase();
        $database->update($this->table, $data)
            ->where(["id" => $id])
            ->bindParams();

        try {
            return [$database->execute()];
        } catch (\Throwable $e) {
            echo $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();
            return [];
        }
    }
    public function inactive(int $id)
    {
        if (array_search($this->table, $this->switchableTables) !== false) {
            throw new InvalidArgumentException("'{$this->table}' visibility cannot be changed");
        }

        $database = new SQLDatabase();
        $database->update($this->table, ['is_active' => 'false'])
            ->where(["id" => $id])
            ->bindParams();

        try {
            $database->execute();

            return true;
        } catch (\Throwable $e) {
            echo $e->getMessage() . " " . $e->getFile() . " " . $e->getLine();
            return [];
        }
    }
}
