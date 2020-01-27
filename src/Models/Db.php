<?php

namespace App\Models;

use Zend\Db\Sql\Sql;
use Zend\Db\Adapter\Adapter;

class Db
{
    private $adapter;

    /**
     * Db constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->adapter = new Adapter($config);
    }

    /**
     * @return object
     */
    public function getConfig()
    {
        $sql = new Sql($this->adapter);
        $select = $sql->select();
        $select->from('configurations')
            ->where([
                'status' => 1,
            ]);
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        $settings = [];

        foreach ($results as $result) {
            $settings[$result['setting_key']] = $result['setting_value'];
        }

        return (object)$settings;
    }
}