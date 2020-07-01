<?php


/**
 * Interface DB
 */
interface DB
{
    /**
     * @param array $fields
     * @param string $table
     * @param string $order
     * @param int $start
     * @param int $countRec
     * @return array
     */
    public function select(array $fields, string $table, string $order, int $start =0, int $countRec = 100):array;

    /**
     * @param array $fields
     * @param string $table
     * @param array $value
     * @return array
     */
    public function insert(array $fields, string $table, array $value):array;

    /**
     * @param array $fields
     * @param string $table
     * @param array $value
     * @param int $id
     * @return array
     */
    public function update(array $fields, string $table, array $value, int $id):array;

    /**
     * @param int $id
     * @param string $table
     * @return array
     */
    public function delete(int $id, string $table):array;
}