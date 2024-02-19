<?php

use Ipeweb\RecapSheets\Database\SQLDatabase;
use Ipeweb\RecapSheets\Exceptions\InvalidSqlWhereConditions;
use Ipeweb\RecapSheets\Exceptions\SqlSyntaxException;
use PHPUnit\Framework\TestCase;

class SQLGeneratorTest extends TestCase
{
    private readonly SQLDatabase $db;
    public function setUp(): void
    {
        $this->db = new SQLDatabase();
    }

    public function testSqlGeneratorInsertAndException()
    {
        $this->expectException(SqlSyntaxException::class);
        $this->expectExceptionMessage("Is not possible to use a where clause in e 'INSERT INTO' SQL query");
        $result = $this->db->insert('users', ['name' => 'user_test']);

        $this->assertEquals('INSERT INTO users (name) VALUES (:ins_name)', $result->getQuery());
        $this->assertEquals('INSERT INTO users (name) VALUES (\'user_test\')', $result->bindParams()->getQuery());

        $this->db->where(['name' => "user_name"]);
    }

    public function testSqlSyntaxException()
    {
        $this->expectException(SqlSyntaxException::class);
        $this->expectExceptionMessage("Is not possible to use a where clause in e 'INSERT INTO' SQL query");

        $this->db->insert('users', ['name' => 'user_test'])->whereBetween("id", '0', '1');
    }

    public function testSqlGeneratorSelectAndInvalidConditionDetection()
    {
        $this->expectException(InvalidSqlWhereConditions::class);
        $this->expectExceptionMessage('Invalid condition argument detected on $conditions[\'birthday\' => \'\']');

        $this->db->select('users')->where(['name' => 'name', 'username' => 'user_name']);
        $this->assertEquals('SELECT * FROM users WHERE name = :whr_name AND username = :whr_username', $this->db->getQuery());
        $this->assertEquals('SELECT * FROM users WHERE name = \'name\' AND username = \'user_name\'', $this->db->bindParams()->getQuery());

        $this->db->where(['birthday' => null]);
    }
}
