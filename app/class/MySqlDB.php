<?php


/**
 * Class MySqlDB
 */
class MySqlDB implements DB
{
    /**
     * @var PDO
     */
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

    /**
     * @param array $fields
     * @param string $table
     * @param string $order
     * @param int $start
     * @param int $countRec
     * @return array
     */
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

    /**
     * @param $sql
     * @return array
     */
    public function execSelect($sql): array
    {
        $query = $this->dbLink->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $query->execute();
        return $query->fetchAll();
    }

    /**
     * @param string $table
     * @return mixed
     */
    public function getCount(string $table)
    {
        $sql = "select COUNT(*) as countData from $table";
        $result = $this->execSelect($sql);
        return $result[0][0];
    }

    /**
     * @param array $fields
     * @param string $table
     * @param array $value
     * @return array
     */
    public function insert(array $fields, string $table, array $value): array
    {
        $sql = "insert into $table (" . implode(",", $fields) . ") values (" .
            implode(",", array_map(function ($a) {
                return $a = ':' . $a;
            }, $fields)) . ")";

        return $this->execChange($sql, $fields, $value);
    }

    /**
     * @param $sql
     * @param array $fields
     * @param array $value
     * @return array
     */
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

    /**
     * @param array $fields
     * @param string $table
     * @param array $value
     * @param int $id
     * @return array
     */
    public function update(array $fields, string $table, array $value, int $id): array
    {
        $sql = "update $table set " . implode(',',
                array_map(function ($field) {
                    return "$field = :$field";
                }, $fields)) . " where id = $id";

        return $this->execChange($sql, $fields, $value);
    }

    /**
     * @param int $id
     * @param string $table
     * @return array
     */
    public function delete(int $id, string $table):array
    {
        $sql ="delete from $table where id = $id";
        return $this->execChange($sql, array(), array());
    }
}