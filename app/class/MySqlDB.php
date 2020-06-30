<?php


class MySqlDB implements DB
{
    private PDO $dbLink;

    /**
     * MySqlDB constructor.
     * @param String $host
     * @param String $dbname
     * @param String $user
     * @param String $pwd
     */
    public function __construct(string $host, string $dbname, string $user, string $pwd)
    {
        $dsn = "mysql:host=$host;dbname=$dbname";
        $this->dbLink = new PDO($dsn, $user, $pwd);
    }

    public function select(array $fields,
                           string $table,
                           string $order,
                           int $start = 0,
                           int $countRec = 100): array
    {
        $sql = "select " . implode(",", $fields) . " from $table \n";
        $sql .= "order by $order limit $start, $countRec";

        return $this->execSelect($sql);
    }

    public function execSelect($sql): array
    {
        $query = $this->dbLink->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $query->execute();
        return $query->fetchAll();
    }

    public function getCount(string $table)
    {
        $sql = "select COUNT(*) as countData from $table";
        $result = $this->execSelect($sql);
        return $result[0][0];
    }

    public function insert(array $fields, string $table, array $value): array
    {
        $sql = "insert into $table (" . implode(",", $fields) . ") values (" .
            implode(",", array_map(function ($a) {
                return $a = ':' . $a;
            }, $fields)) . ")";

        return $this->execChange($sql, $fields, $value);
    }

    public function execChange($sql, array $fields, array $value): array
    {
        $param = array_combine(array_map(function ($a) {
            return $a = ':' . $a;
        }, $fields),
            $value);

        $result = array();

        try {
            $query = $this->dbLink->prepare($sql);
            $query->execute($param);
        } catch (Throwable $err) {
            $result['errorInfo'] = $this->dbLink->errorInfo();
            $result['error'] = $err;
        }

        $result['rows'] = $query->rowCount();
        $result['insertId'] = $this->dbLink->lastInsertId();
        $result['errorCode'] = $this->dbLink->errorCode();

        return $result;
    }

    public function update(array $fields, string $table, array $value, int $id): array
    {
        $sql = "update $table set " . implode(',',
                array_map(function ($field) {
                    return "$field = :$field";
                }, $fields)) . " where id = $id";

        return $this->execChange($sql, $fields, $value);
    }

    public function delete(int $id, string $table):array
    {
        $sql ="delete from $table where id = $id";
        return $this->execChange($sql, array(), array());
    }
}