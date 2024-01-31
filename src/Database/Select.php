<?php

namespace Ipeweb\IpeSheets\Database;

use Ipeweb\IpeSheets\Exceptions\MissingRequiredParameterException;
use Ipeweb\IpeSheets\Services\Validations;

class Select
{
    public const SQL_ALL = '*';

    private ?string $table_name = null;
    private ?string $table_alias = null;
    private ?string $column_name = null;
    private ?string $column_alias = null;

    /** The constructor of select class
     * 
     * @param mixed $data This variable can have the next values:
     * * Column name as: `$data["column_name"]` ⇒ *required*.
     * * Column alias as: `$data["column_alias"]` ⇒ *optional*.
     * * Table name as: `$data["table_name"]` ⇒ *optional*.
     * * Table alias as: `$data["table_alias"]` ⇒ *optional*.
     */
    public function __construct(array|null $data)
    {
        if ($data === null) {
            $this->column_name = self::SQL_ALL;
            return;
        }

        if (!array_key_exists('column_name', $data)) {
            throw new MissingRequiredParameterException(["column_name"]);
        }

        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function getSelect(): string
    {
        if ($this->column_name == self::SQL_ALL) {
            return self::SQL_ALL;
        }

        $select = $this->table_alias ? ($this->table_alias . '.') : ($this->table_name ? ($this->table_name . '.') : '');
        $select .= $this->column_name . ($this->column_alias ? " AS {$this->column_alias}" : "");

        return $select;
    }
}