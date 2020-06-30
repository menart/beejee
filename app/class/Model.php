<?php


/**
 * Class Model
 */
class Model
{

    /**
     * @var DB
     */
    private DB $db;

    /**
     * Model constructor.
     * @param DB $db
     */
    public function __construct(DB $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $className
     * @param int $page
     * @param int $countTask
     * @param string $order
     * @return array
     * @throws ReflectionException
     */
    public function getList(string $className, int $page, int $countTask, string $order): array
    {

        $fields = array();
        $class = new ReflectionClass($className);

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $field) {
            array_push($fields, $field->getName());
        }

        $returnArray = array();

        if (!in_array(strtolower($order), array_map('strtolower', $fields)))
            $order = $fields[0];

        $result = $this->db->select($fields,
            't' . $className,
            $order,
            $page * $countTask,
            $countTask);

        if (count($result) > 0) {
            foreach ($result as $row) {
                $obj = $class->newInstance();
                foreach ($fields as $field) {
                    $obj->$field = $row[$field];
                }
                array_push($returnArray, $obj);
            }

        }

        return $returnArray;
    }

    /**
     * @param $class
     * @return mixed
     */
    public function GetCount($class)
    {
        return $this->db->getCount('t' . $class);
    }

    /**
     * @param DBObject $obj
     * @return int
     */
    public function insertValue(DBObject $obj): array
    {
        $class = new ReflectionClass(get_class($obj));

        $fields = array();
        $values = array();

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $field) {
            if ($field->isInitialized($obj)) {
                $fieldsName = $field->getName();
                $value = $obj->$fieldsName;
                if ($value !== 0) {
                    array_push($fields, $field->getName());
                    array_push($values, $value);
                }
            }
        }

        return $this->db->insert($fields,'t'.get_class($obj), $values);
    }

    /**
     * @param DBObject $obj
     * @return array
     * @throws ReflectionException
     */
    public function updateValue(DBObject $obj): array
    {
        $class = new ReflectionClass(get_class($obj));

        $fields = array();
        $values = array();

        foreach ($class->getProperties(ReflectionProperty::IS_PUBLIC) as $field) {
            if ($field->isInitialized($obj)) {
                $fieldsName = $field->getName();
                $value = $obj->$fieldsName;
                if ($fieldsName != 'id') {
                    array_push($fields, $field->getName());
                    array_push($values, $value);
                }
            }
        }

        return $this->db->update($fields,'t'.get_class($obj), $values, $obj->id);
    }

    /**
     * @param $className
     * @param $id
     * @return array
     */
    public function deleteValue($className, $id)
    {
        return $this->db->delete($id,'t'.$className);
    }
}