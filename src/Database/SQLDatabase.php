<?php

namespace Ipeweb\RecapSheets\Database;

use Ipeweb\RecapSheets\Exceptions\InvalidSqlWhereConditions;
use Ipeweb\RecapSheets\Exceptions\SqlSyntaxException;
use PDO;

class SQLDatabase
{
    public const SQL_STAR = "*";

    private int $limit = 0, $offset = 0;
    private string $query;
    private array $params = [];


    /**
     * Initialize a select clause.
     * 
     * @param  mixed $select A mixed value that can be either a: 
     *                       single string referring to a single column from a table;
     *                       a array that refer to multiple columns and set alias to all of them;
     *                       or the predefined sql star that refers all columns.
     * @param  mixed $from   A mixed value that can be either a:
     *                       single string referring to the main
     *                       table; or a array that will be read
     *                       as `["column" => "alias"]`.
     * @return SQLDatabase
     */
    public function select($from, $select = SQLDatabase::SQL_STAR): SQLDatabase
    {
        $table = is_array($from) ? array_key_first($from) : $from;
        $tableAlias = is_array($from) ? $from[$table] : $from;


        if (is_array($select)) {
            $selectColumns = array_map(
                function ($column, $as) use ($tableAlias) {
                    return ($column !== "" && is_numeric($column))
                        ? "{$tableAlias}.{$as}"
                        : "{$tableAlias}.{$column} AS $as";
                },
                array_keys($select),
                $select
            );

            $selectColumns = implode(', ', $selectColumns);
        } else {
            $selectColumns = $select;
        }

        $tableExpression = (!is_array($from)) ? $table : "{$table} AS {$tableAlias}";
        $this->query = "SELECT $selectColumns FROM $tableExpression";

        return $this;
    }

    /**
     * Initialize a insert clause.
     * 
     * @param  string $table  Defines the table where the data will be inserted.
     * @param  array  $values Define an array `[$column => $data]` that will be added to the database.
     * @return SQLDatabase
     */
    public function insert(string $table, array $values): SQLDatabase
    {
        $columns = implode(', ', array_keys($values));
        $placeholders = "";

        foreach ($values as $key => $value) {
            $placeholders .= ":ins_{$key}, ";
            $this->params[":ins_{$key}"] = $value;
        }

        $placeholders = substr($placeholders, 0, -2);

        $this->query = "INSERT INTO $table ($columns) VALUES ($placeholders)";
        $this->trimQuery();

        return $this;
    }

    /**
     * Initialize a update clause.
     * 
     * @param  string $table  Defines the table where the data will be inserted.
     * @param  array  $values Define an array `[$column => $data]` that will be added to the database.
     * @return SQLDatabase
     */
    public function update(string $table, array $values): SQLDatabase
    {
        unset($this->params);
        $setClause = [];

        foreach ($values as $key => $value) {
            $setClause[] = "{$key} = :upd_{$key}";
            $this->params[":upd_{$key}"] = "{$value}";
        }

        $setClause = implode(', ', $setClause);
        $this->query = "UPDATE $table SET $setClause";
        $this->trimQuery();

        return $this;
    }

    /**
     * Adds a "where" clause to the query sentence.
     * 
     * @param  array  $conditions When passing conditions, the array should be similar to: `["column" => "Sequence"]`.
     * @param  string $operator   The operator param should be `=`, `<=` or `>=`.
     * @param  bool   $strict     The strict param indicates if the clause `LIKE` should be used creating the where clause.
     *                            * `$strict = true` means that `LIKE` will not be used in query. * `$strict = false` means
     *                            that `LIKE` will be used in query.
     * @return SQLDatabase
     */
    public function where(
        array $conditions,
        string $operator = '=',
        string $conditional = 'AND',
        bool $strict = true
    ): SQLDatabase {
        if (str_contains($this->query, 'INSERT INTO')) {
            throw new SqlSyntaxException("Is not possible to use a where clause in e 'INSERT INTO' SQL query");
        }

        $whereClause = "";

        if (!empty($conditions)) {
            if (!str_contains($this->query, "WHERE")) {
                $whereClause = " WHERE ";
            } else {
                $whereClause = " $conditional ";
            }

            $conditionsArray = [];

            foreach ($conditions as $column => $value) {
                if ($column == null or $column == "" or $value == null or $value == "") {
                    throw new InvalidSqlWhereConditions("Invalid condition argument detected on \$conditions['{$column}' => '{$value}']");
                }

                if ($column !== "id" or $column === "google_id" or !str_contains($column, "_id")) {
                    $conditionsArray[] = "{$column} " . ($strict ? "{$operator} :whr_{$column}" : " ILIKE :whr_like_{$column}");
                } else {
                    $conditionsArray[] = "{$column} " . "=" . " :whr_{$column}";
                }

                $column === "google_id" ?
                    $this->params[($strict ? ":whr_{$column}" : ":whr_like_{$column}")] = "{$value}GOOGLE_TEMPLATE" :
                    $this->params[($strict ? ":whr_{$column}" : ":whr_like_{$column}")] = "{$value}";
            }

            $whereClause .= implode(" {$conditional} ", $conditionsArray);

            $this->query .= $whereClause;
        }

        $this->trimQuery();
        return $this;
    }

    /**
     * Adds `BETWEEN` to the query.
     * 
     * @param  string $target      The field that will be compared.
     * @param  string $start       The starter value.
     * @param  string $end         The last value.
     * @param  string $conditional The conditional that will be added in case the query already has a `WHERE`.
     * @throws \Ipeweb\RecapSheets\Exceptions\SqlSyntaxException
     * @return \Ipeweb\RecapSheets\Database\SQLDatabase
     */
    public function whereBetween(string $target, string $start, string $end, string $conditional = "AND"): SQLDatabase
    {
        if (str_contains($this->query, 'INSERT INTO')) {
            throw new SqlSyntaxException("Is not possible to use a where clause in e 'INSERT INTO' SQL query");
        }

        $whereClause = "";
        if (!str_contains($this->query, "WHERE")) {
            $whereClause = " WHERE ";
        } else {
            $whereClause = " {$conditional} ";
        }

        $whereClause .= " {$target} BETWEEN {$start} AND {$end} ";

        $this->query .= $whereClause;

        $this->trimQuery();
        return $this;
    }

    /**
     * Bind all parameters passed along the execution of the commands.
     * 
     * @return SQLDatabase
     */
    public function bindParams(): SQLDatabase
    {
        foreach ($this->params as $key => $value) {
            if (!is_numeric($value)) {
                if (str_contains($key, '_like_')) {
                    $value = "'%{$value}%'";
                } else {
                    if ($value === "CURRENT_TIMESTAMP") {
                        $value = "{$value}";
                    } else {
                        $value = "'{$value}'";
                    }
                }
            }
            $this->query = str_replace($key, is_bool($value) ? ($value ? "true" : "false") : "{$value}", $this->query);
        }

        $this->query = str_replace('GOOGLE_TEMPLATE', '', $this->query);

        $this->trimQuery();
        return $this;
    }

    /**
     * Removes all unnecessary spaces inside the query string.
     * 
     * @return void
     */
    public function trimQuery()
    {
        do {
            $this->query = str_replace("  ", " ", $this->query);
        } while (str_contains($this->query, "  "));

        $this->query = trim($this->query);
    }

    /**
     * Adds limit to the query statement. The database may contain too many data, the "limit" statement adds a quantity of records it can return.
     * 
     * @param  int $limit
     * @throws \InvalidArgumentException
     * @return SQLDatabase
     */
    public function limit(int $limit): SQLDatabase
    {
        if ($limit < 0) {
            throw new \InvalidArgumentException("Limit cannot be a negative numbers");
        }

        $this->limit = $limit;
        $this->query .= " LIMIT $limit";
        return $this;
    }

    /**
     * Adds offset to the query statement. The "offset" statement is used to tell SQL from how many records it should start selecting.
     * 
     * @param  int $offset A positive number, greater than zero.
     * @throws \InvalidArgumentException
     * @return SQLDatabase
     */
    public function offset(int $offset): SQLDatabase
    {
        if ($offset < 0) {
            throw new \InvalidArgumentException("Offset cannot start from negative numbers");
        }

        $this->query .= " OFFSET $offset";
        return $this;
    }

    /**
     * Adds a order by clause at the end of the query.
     * 
     * @param  string $field     The name of the column that will be used to ordinate.
     * @param  string $direction Defines the direction of the ordination.
     * @return \Ipeweb\RecapSheets\Database\SQLDatabase
     */
    public function orderBy(
        string $field = "id",
        string $direction = "ASC"
    ): SQLDatabase {
        if (empty($this->query)) {
            throw new \InvalidArgumentException("No queries found to place order by");
        }
        if (str_contains($this->query, "ORDER BY")) {
            throw new \InvalidArgumentException("The query statement already contains a order by");
        }

        $this->query = $this->query . " ORDER BY {$field} {$direction}";

        return $this;
    }

    /**
     * Returns the current query string.
     * 
     * @return string
     */
    public function getQuery(): string
    {
        $this->trimQuery();
        return $this->query;
    }

    /**
     * Overrides the current query with a pre-made one.
     * 
     * @param  string $query The new statement string.
     * @return \Ipeweb\RecapSheets\Database\SQLDatabase
     */
    public function setQuery(string $query): SQLDatabase
    {
        $this->query = $query;
        $this->trimQuery();

        return $this;
    }

    /**
     * Execute the generated sql statement and fetch it to a array
     *
     * @throws \InvalidArgumentException
     * @return array|int
     */
    public function execute(): array | int
    {
        $fetchMode = 'fetchAll';

        if ($this->query == "" or $this->query == null) {
            throw new \InvalidArgumentException("No queries found to perform a request");
        }

        $this->trimQuery();

        try {
            $pdo = PDOConnection::getPdoInstance();

            if (str_contains($this->query, "INSERT INTO users") || str_contains($this->query, "INSERT INTO projects") || str_contains($this->query, "INSERT INTO cards") || str_contains($this->query, "INSERT INTO themes") || str_contains($this->query, "UPDATE")) {
                $this->query .= " RETURNING * ";
                $this->trimQuery();
                $fetchMode = 'fetch';
            }

            $stmt = $pdo->prepare($this->query);

            $stmt->execute();

            $result = $stmt->$fetchMode(PDO::FETCH_ASSOC);

            return $result;
        } catch (\Throwable $th) {
            http_response_code(400);
            throw new \Exception("Invalid generated query: " . $th->getMessage() . " " . $th->getFile() . " " . $th->getLine());
        }
    }
}
