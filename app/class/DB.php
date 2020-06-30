<?php


interface DB
{
    public function select(array $fields, string $table, string $order, int $start =0, int $countRec = 100):array;
    public function insert(array $fields, string $table, array $value):array;
    public function update(array $fields, string $table, array $value, int $id):array;
    public function delete(int $id, string $table):array;
}