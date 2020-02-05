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
                $settings[$config->config_key] = $config->config_value;
            }
        }

        return (object)$settings;
    }

    /**
     * @param $config_key
     * @param $config_value
     * @return bool|int
     */
    public function updateConfig($config_key, $config_value)
    {
        $configurations = $this->db::table('configurations')->where(['config_key' => $config_key])->get();

        $success = false;
        if ($configurations->count()) {
            $config_id = $configurations->first()->config_id;
            $success = $this->db::table('configurations')
                ->where(['config_id' => $config_id])
                ->update([
                    'config_key' => $config_key,
                    'config_value' => $config_value,
                    'date_updated' => date('Y-m-d H:i:s'),
                ]);
        }

        return $success;
    }

    /**
     * @param $record
     * @return bool
     */
    public function addRecord($record)
    {
        $dns_record_id = null;
        if (!empty($record['dns_record_id'])) {
            $dns_record = $this->db::table('dns_records')->where([
                'dns_record_id' => $record['dns_record_id'],
            ])->get();
            if ($dns_record->count()) $dns_record_id = $dns_record->first()->dns_record_id;
        }

        if (!empty($dns_record_id)) {
            $record['date_updated'] = date('Y-m-d H:i:s');
            $success = $this->db::table('dns_records')
                ->where(['dns_record_id' => $dns_record_id])
                ->update($record);
        } else {
            $record['date_created'] = date('Y-m-d H:i:s');
            $success = $this->db::table('dns_records')
                ->insert($record);
        }

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