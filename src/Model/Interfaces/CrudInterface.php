<?php

namespace Ipeweb\IpeSheets\Model\Interfaces;

interface CrudInterface
{
    public function insert(array $data): int;
    public function get(string $key, string $value): array;
    public function getSearch(array $data, int $offset = 1, int $limit = 25, ?array $order = null): array;
    public function getAll(int $offset = 1, int $limit = 25, ?array $order = null): array;
    public function update(int $id, array $data);
    public function inactive(int $id);
}
