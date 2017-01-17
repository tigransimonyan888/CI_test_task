<?php

class Base_Model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }

    protected function duplicate_key_update($table, $values)
    {
        $updatestr = array();
        $keystr    = array();
        $valstr    = array();

        foreach($values as $key => $val)
        {
            $updatestr[] = "$key = '$val'";
            $keystr[]    = $key;
            $valstr[]    = "'$val'";
        }

        $sql  = "INSERT INTO ".$table." (".implode(', ', $keystr).") ";
        $sql .= "VALUES (".implode(', ', $valstr).") ";
        $sql .= "ON DUPLICATE KEY UPDATE ".implode(', ', $updatestr);

        if ($this->db->query($sql)){
            return $this->db->insert_id();
        }
        return false;
    }
}