<?php

namespace Ipeweb\IpeSheets\Database;

use Ipeweb\IpeSheets\Services\Utils;
use Ipeweb\IpeSheets\Services\Validations;

class ReWorkSQLDatabase
{
    /**
     * Properties
     */

    private const SELECT_STATEMENT = 1;
    private const INSERT_STATEMENT = 2;
    private const UPDATE_STATEMENT = 3;
    private const DELETE_STATEMENT = 4;



    /**
     * Stores the last generated *SQL* query. This variable will gain value after the first `$this->execute();` function call.
     * @var string $previousQuery
     */
    private ?string $previousQuery = null;

    /**
     * Stores the mixed value of the last `$this->execute();` returned result.
     * @var string $previousResult When it returns a single result that does not need to be stored in an array.
     * @var array $previousResult Used in almost all cases, since most of the time `$this->execute();` returns an array.
     */
    private mixed $previousResult = null;

    /**
     * Stores the mixed value of `$this->execute();` returned result.
     * @var string $previousResult When it returns a single result that does not need to be stored in an array.
     * @var array $previousResult Used in almost all cases, since most of the time `$this->execute();` returns an array.
     */
    private mixed $result = null;

    /**
     * Stores the type of query that will be executed.
     * @var string $queryType
     */
    private string $queryType;

    /**
     * Stores all the data that will be sent using associative array.
     * * The data `[$key => $value]` is read like `[':user' => 'John Doe']` or else it will return an SQL error/exception.
     * @var array $params
     */
    private array $params = [];

    /**
     * Stores an array of elements with data type **Ipeweb\IpeSheets\Database\Select**.
     * @var array $selectArray
     */
    private array $selectArray = [];

    /**
     * Stores data type of all used columns.
     * The attribute is responsible for storing the mapping of database column names to their corresponding data types.
     * 
     * The array structure should follow the pattern `["database_name+column_name" => "data_type"]`.
     * @var array $storedColumnDataType
     */
    private array $storedColumnDataType = [];

    /**
     * Stores the main SQL statement.
     * Example: `INSERT INTO table (...) VALUES (...)` or `SELECT column_name FROM table_name`.
     * @var string
     */
    public string $queryStart = "";

    /**
     * Stores the current state of generated query.
     * @var string $query
     */
    private string $query = "";

    public function select(array|string|Select $selectArray, string|array $table): ReWorkSQLDatabase
    {
        $this->queryStart = 'SELECT ';

        $preparedTable = is_array($table) ? implode(" ", [$table[0] => $table[1]]) : $table;

        if ($selectArray instanceof Select) {
            $this->queryStart .= $selectArray->getSelect() . ' FROM ' . $preparedTable;
        } elseif (!is_array($selectArray)) {
            $this->queryStart .= Select::SQL_ALL . ' FROM ' . (is_array($table) ? implode(" ", $table) : $table);
        } else {
            $this->queryType = self::SELECT_STATEMENT;
            $selectStringArray = [];

            foreach ($selectArray as $select) {
                $selectStringArray[] = $select instanceof Select ? $select->getSelect() : $select;
            }

            $this->queryStart .= implode(", ", $selectStringArray) . ' FROM ' . $preparedTable;
        }

        return $this;
    }




    /**
     * Removes all unnecessary spaces inside the query string.
     * It is called when `$this->setQuery()` method is used.
     * 
     * @return ReWorkSQLDatabase
     */
    public function normalizeQuery()
    {
        do {
            $this->query = str_replace("  ", " ", $this->query);
        } while (str_contains($this->query, "  "));

        return $this;
    }
}
