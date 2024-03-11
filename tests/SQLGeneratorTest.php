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

    public function testSelect()
    {
        $query = $this->db->select(['users' => 'users'], ['id' => 'user_id'])->getQuery();
        $this->assertEquals("SELECT users.id AS user_id FROM users AS users", $query);
    }

    public function testInsert()
    {
        $query = $this->db->insert('users', ['name' => 'John', 'email' => 'john@example.com'])->getQuery();
        $this->assertEquals("INSERT INTO users (name, email) VALUES (:ins_name, :ins_email)", $query);
    }

    public function testUpdate()
    {
        $query = $this->db->update('users', ['name' => 'John'])->getQuery();
        $this->assertEquals("UPDATE users SET name = :upd_name", $query);
    }

    public function testWhere()
    {
        $query = $this->db->select('users')->where(['name' => 'John'])->getQuery();
        $this->assertEquals("SELECT * FROM users WHERE name = :whr_name", $query);
    }

    public function testWhereBetween()
    {
        $query = $this->db->select('users')->whereBetween('id', 1, 10)->getQuery();
        $this->assertEquals("SELECT * FROM users WHERE id BETWEEN 1 AND 10", $query);
    }

    public function testLimit()
    {
        $query = $this->db->select('users')->limit(10)->getQuery();
        $this->assertEquals("SELECT * FROM users LIMIT 10", $query);
    }

    public function testOffset()
    {
        $query = $this->db->select('users')->offset(5)->getQuery();
        $this->assertEquals("SELECT * FROM users OFFSET 5", $query);
    }

    public function testOrderBy()
    {
        $query = $this->db->select('users')->orderBy('name', 'ASC')->getQuery();
        $this->assertEquals("SELECT * FROM users ORDER BY name ASC", $query);
    }

    public function testBindParams()
    {
        $this->db->insert('users', ['name' => 'John', 'email' => 'john@example.com'])->bindParams();
        $query = $this->db->getQuery();
        $this->assertEquals("INSERT INTO users (name, email) VALUES ('John', 'john@example.com')", $query);
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