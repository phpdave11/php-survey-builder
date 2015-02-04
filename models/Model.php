<?php

/**
 * The Model is an abstract class used to represent a database table. The Model class
 * includes several useful functions for interacting with the database.
 *
 * @author David Barnes
 * @copyright Copyright (c) 2013, David Barnes
 */
abstract class Model
{
    /**
     * Get the fields for the table associated with the model
     *
     * @return string the fields associated with the model
     */
    public static function getFields()
    {
        return static::$fields;
    }

    /**
     * Get the primary key associated with the model
     *
     * @return string the primary key associated with the model
     */
    public static function getPrimaryKey()
    {
        return static::$primaryKey;
    }

    /**
     * Get the database table name associated with the model
     *
     * Get the table name associated with the table
     */
    public static function getTable()
    {
        return self::decamelize(get_called_class());
    }

    /**
     * Convert CamelCase to camel_case
     *
     * @return string converted from CamelCase from camel_case
     */
    public static function decamelize($str)
    {
        return strtolower(preg_replace('/(?!^)[[:upper:]][[:lower:]]/', '$0', preg_replace('/(?!^)[[:upper:]]+/', '_$0', $str)));
    }

    /**
     * Query the database for a record based on a primary key
     *
     * @param PDO $pdo the database to search in
     * @param int $id the primary key value
     * @return Model returns the Model object subclassed to the table name camel-cased
     */
    public static function queryRecordById(PDO $pdo, $id)
    {
        $fields = implode(', ', static::getFields());
        $table = static::getTable();
        $primaryKey = static::getPrimaryKey();
        $params = array('primary_key' => $id);

        $sql = "select $fields from $table where $primaryKey = :primary_key";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetch();
    }

    /**
     * Query the database for a record based on a where clause with bound parameters
     *
     * @param PDO $pdo the database to search in
     * @param string $where the where SQL clause
     * @param array $params the prepared statement bound parameters
     * @return Model returns the Model object subclassed to the table name camel-cased
     */
    public static function queryRecordWithWhereClause(PDO $pdo, $where, $params=null)
    {
        $fields = implode(', ', static::getFields());
        $table = static::getTable();
        $primaryKey = static::getPrimaryKey();

        $sql = "select $fields from $table where $where";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetch();
    }

    /**
     * Query the database for an array of records based on a where clause with bound parameters
     *
     * @param PDO $pdo the database to search in
     * @param string $where the where SQL clause
     * @param array $params the prepared statement bound parameters
     * @return array returns an array of the Model object subclassed to the table name camel-cased
     */
    public static function queryRecordsWithWhereClause(PDO $pdo, $where, $params=null)
    {
        $fields = implode(', ', static::getFields());
        $table = static::getTable();
        $primaryKey = static::getPrimaryKey();

        $sql = "select $fields from $table where $where";
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetchAll();
    }

    /**
     * Query the database for an array of records based on an array with search parameters
     *
     * @param PDO $pdo the database to search in
     * @param array $search the search parameters, including 'sort' to incidate the sort order
     * @return array returns an array of the Model object subclassed to the table name camel-cased
     */
    public static function queryRecords(PDO $pdo, $search=null)
    {
        $fields = static::getFields();
        $table = static::getTable();
        $primaryKey = static::getPrimaryKey();

        $whereFields = array();
        $params = array();
        if (empty($search))
            $search = array();

        foreach ($search as $field => $value)
        {
            if (in_array($field, $fields))
            {
                $whereFields[] = "$field = :$field";
                $params[$field] = $value;
            }
        }

        $where = implode(', ', $whereFields);

        $fieldsSql = implode(', ', $fields);
        $sql = "select $fieldsSql from $table";
        if (!empty($where))
            $sql .= " where $where";

        if (isset($search['sort']) && in_array($search['sort'], $fields))
            $sql .= " order by " . $search['sort'];

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetchAll();
    }

    /**
     * Query the database for a given SQL query with bound parameters
     *
     * @param PDO $pdo the database to search in
     * @param string $sql the SQL query to execute
     * @param array $params the parameters to bind to the prepared statement
     * @return array returns an array of the Model object subclassed to the table name camel-cased
     */
    public static function querySql(PDO $pdo, $sql, $params=null)
    {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        return $stmt->fetchAll();
    }

    /**
     * Model constructor - initialise $fields as properties of class instance
     */
    public function __construct()
    {
        $fields = static::getFields();

        foreach ($fields as $field)
            if (!isset($this->$field))
                $this->$field = null;
    }

    /**
     * Set the values of class instance to $values
     * 
     * @param array $values the associative array to set the object values to
     */
    public function setValues($values)
    {
        $fields = static::getFields();

        foreach ($values as $field => $value)
            if (in_array($field, $fields))
                $this->$field = $value;
    }

    /**
     * Set the values of class instance to $values, except ignore empty values
     * 
     * @param array $values the associative array to set the object values to
     */
    public function updateValues($values)
    {
        $fields = static::getFields();

        foreach ($values as $field => $value)
            if (in_array($field, $fields) && !empty($value))
                $this->$field = $value;
    }

    /**
     * Delete the record from the database based on the primary key value
     *
     * @param PDO $pdo the database to search in
     */
    public function deleteRecord(PDO $pdo)
    {
        $fields = static::getFields();
        $table = static::getTable();
        $primaryKey = static::getPrimaryKey();

        if (!empty($this->$primaryKey))
        {
            $sql = "delete from $table where $primaryKey = :primary_key";

            $params = array();
            $params['primary_key'] = $this->$primaryKey;

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }
    }

    /**
     * Update or insert the record in the database
     *
     * @param PDO $pdo the database to search in
     */
    public function storeRecord(PDO $pdo)
    {
        $fields = static::getFields();
        $table = static::getTable();
        $primaryKey = static::getPrimaryKey();

        // If the primary key is empty, then do an insert
        if (empty($this->$primaryKey))
        {
            $params = array();

            foreach ($fields as $i => $field)
                if ($field == $primaryKey)
                    unset($fields[$i]);

            $fieldSql = implode(', ', $fields);

            $valueArray = array();
            foreach ($fields as $field)
            {
                $valueArray[] = ":$field";
                $params[$field] = $this->$field;
            }

            $valueSql = implode(', ', $valueArray);

            $sql = "insert into $table ($fieldSql) values ($valueSql)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            $this->$primaryKey = $pdo->lastInsertId();
        }
        // If the primary key is not empty, then do an update
        else
        {
            $params = array();

            foreach ($fields as $i => $field)
                if ($field == $primaryKey)
                    unset($fields[$i]);

            $fieldArray = array();
            foreach ($fields as $field)
            {
                $fieldArray[] = "$field = :$field";
                $params[$field] = $this->$field;
            }

            $updateSql = implode(', ', $fieldArray);

            $params['primary_key'] = $this->$primaryKey;

            $sql = "update $table set $updateSql where $primaryKey = :primary_key";
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
        }
    }
}

?>
