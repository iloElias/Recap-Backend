<?php

namespace Ipeweb\RecapSheets\Model\Interfaces;

interface CrudInterface
{
    public function insert(array $data): array;
    public function get(array $data): array;
    public function getSearch(array $data, int $offset = 0, int $limit = 25, ?array $order = null): array;
    public function getAll(int $offset = 0, int $limit = 25, ?array $order = null): array;
    public function update(int $id, array $data);
    public function inactive(int $id);
}
