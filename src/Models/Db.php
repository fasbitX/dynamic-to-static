<?php

namespace App\Models;

use Illuminate\Database\Capsule\Manager as Capsule;

class Db
{
    private $db;

    /**
     * Db constructor.
     * @param $config
     */
    public function __construct($config)
    {
        $this->db = new Capsule;
        $this->db->addConnection($config);
        $this->db->setAsGlobal();
        $this->db->bootEloquent();
    }

    /**
     * @return object
     */
    public function getConfig()
    {
        $configurations = $this->db::table('configurations')->where(['status' => 1])->get();
        $settings = [];
        if ($configurations->count()) {
            foreach ($configurations as $config) {
                $settings[$config->setting_key] = $config->setting_value;
            }
        }

        return (object)$settings;
    }

    /**
     * @param $record
     * @return bool
     */
    public function addRecord($record)
    {
        $dns_record_id = null;
        $dns_record = $this->db::table('dns_records')->where([
            'record_type' => $record['record_type'],
            'record_name' => $record['record_name'],
        ])->get();
        if ($dns_record->count()) {
            $record['date_updated'] = date('Y-m-d H:i:s');
            $dns_record_id = $dns_record->first()->dns_record_id;
        } else {
            $record['date_created'] = date('Y-m-d H:i:s');
        }

        $success = $this->db::table('dns_records')
            ->updateOrInsert(
                ['dns_record_id' => $dns_record_id],
                $record
            );

        return $success;
    }

    /**
     * @return array
     */
    public function getDnsRecords()
    {
        $dns_records = $this->db::table('dns_records')->get()->all();
        return $dns_records;
    }

    /**
     * @param $delete_record_id
     * @return bool|int
     */
    public function deleteRecord($delete_record_id)
    {
        $success = false;
        if (!empty($delete_record_id)) $success = $this->db::table('dns_records')->where(['dns_record_id' => $delete_record_id])->delete();
        return $success;
    }
}