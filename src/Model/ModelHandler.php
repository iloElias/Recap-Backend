<?php

namespace Ipeweb\RecapSheets\Model;

use InvalidArgumentException;
use Ipeweb\RecapSheets\Database\SQLDatabase;
use Ipeweb\RecapSheets\Exceptions\NotNecessaryDataException;
use Ipeweb\RecapSheets\Model\Interfaces\CrudInterface;
use Ipeweb\RecapSheets\Services\Utils;

class ModelHandler implements CrudInterface
{
    private static ?ModelHandler $modelHandler = null;

    protected string $table = '';

    protected array $switchableTables = ['users', 'projects'];

    protected array $fields = [];

    public static function getModelHandlerInstance(string $table, array $fields = []): ModelHandler
    {
        if (self::$modelHandler === null) {
            self::$modelHandler = new ModelHandler();
        }

        self::$modelHandler->table = $table;
        self::$modelHandler->fields = $fields;

        return self::$modelHandler;
    }

    private function __construct()
    {
    }

    public function insert(array $data): array
    {
        foreach ($data as $key => $value) {
            if (!Utils::arrayFind($this->fields, $key)) {
                throw new InvalidArgumentException(sprintf('Invalid value was sent to use .Can\'t use [\'%s\' => \'%s\'] in this insert', $key, $value));
            }
        }

        $sqlDatabase = new SQLDatabase();
        $sqlDatabase->insert($this->table, $data)
            ->bindParams();

        try {
            return $sqlDatabase->execute();
        } catch (\Throwable $throwable) {
            echo json_encode(
                [
                    "message" => "An error ocurred, the insert was not executed or did not returned the id",
                    "error" => $throwable->getMessage() . " " . $throwable->getFile() . " " . $throwable->getLine()
                ]
            );
            return [];
        }
    }

    public function get(array $data): array
    {
        foreach (array_keys($data) as $key) {
            if (!Utils::arrayFind($this->fields, $key)) {
                throw new NotNecessaryDataException(sprintf('The key \'%s\' was not found in valid fields array', $key));
            }
        }

        $sqlDatabase = new SQLDatabase();
        $sqlDatabase->select($this->table)
            ->where($data)
            ->limit(1)
            ->bindParams();

        try {
            $result = $sqlDatabase->execute();

            if (!$result) {
                return [];
            }

            return $result;
        } catch (\Throwable $throwable) {
            echo $throwable->getMessage() . " " . $throwable->getFile() . " " . $throwable->getLine();
            return [];
        }
    }

    public function getSearch(array $data, int $offset = 0, int $limit = 25, array|null $order = null, $strict = false, $conditional = 'AND'): array
    {
        $blankData = [];
        foreach ($data as $key => $value) {
            if ($key === null || $key === "" || $value === null || $value === "") {
                $blankData[] = sprintf('[%s => %s]', $key, $value);
            }
        }

        if ($blankData !== []) {
            throw new InvalidArgumentException("Some of the received data are invalid or blank: " . implode(',', $blankData));
        }

        $sqlDatabase = new SQLDatabase();
        $sqlDatabase->select($this->table, "*")
            ->where($data, strict: $strict, conditional: $conditional);

        if (in_array('visible', $this->fields)) {
            $sqlDatabase->where(["visible" => 'true']);
            var_dump($this->fields);
        }

        $sqlDatabase->limit($limit)
            ->offset($offset)
            ->bindParams();

        if (isset($order) && isset($order['field'])) {
            $sqlDatabase->orderBy(
                $order['field'],
                strtoupper($order["direction"] ?? "ASC")
            );
        }

        try {
            $result = $sqlDatabase->execute();

            return $result ?? [];
        } catch (\Throwable $throwable) {
            echo $throwable->getMessage() . " " . $throwable->getFile() . " " . $throwable->getLine();
            return [];
        }
    }

    public function getAll(int $offset = 0, int $limit = 25, array|null $order = null): array
    {
        $sqlDatabase = new SQLDatabase();
        $sqlDatabase->select($this->table);

        if (in_array('visible', $this->fields)) {
            $sqlDatabase->where(["visible" => 'true']);
            var_dump($this->fields);
        }

        if (isset($order) && isset($order['field'])) {
            $sqlDatabase->orderBy($order['field'], isset($order["direction"]) ? $order["direction"] : "ASC");
        }

        $sqlDatabase->limit($limit)
            ->offset($offset)
            ->bindParams();

        try {
            $result = $sqlDatabase->execute();

            if (!$result) {
                return [];
            }

            return $result;
        } catch (\Throwable $throwable) {
            echo $throwable->getMessage() . " " . $throwable->getFile() . " " . $throwable->getLine();
            return [];
        }
    }

    public function update(int $id, array $data)
    {
        $sqlDatabase = new SQLDatabase();
        $sqlDatabase->update($this->table, $data)
            ->where(["id" => $id])
            ->bindParams();

        try {
            return [$sqlDatabase->execute()];
        } catch (\Throwable $throwable) {
            echo $throwable->getMessage() . " " . $throwable->getFile() . " " . $throwable->getLine();
            return [];
        }
    }

    public function inactive(int $id)
    {
        if (!in_array($this->table, $this->switchableTables)) {
            throw new InvalidArgumentException(sprintf('\'%s\' visibility cannot be changed', $this->table));
        }

        $sqlDatabase = new SQLDatabase();
        $sqlDatabase->update($this->table, ['state' => 'archived'])
            ->where(["id" => $id])
            ->bindParams();

        try {
            $response = $sqlDatabase->execute();

            return ['success' => true];
        } catch (\Throwable $throwable) {
            http_response_code(500);
            return ['message' => 'Something went wrong on inactivating this ' . $this->table];
        }
    }
}
