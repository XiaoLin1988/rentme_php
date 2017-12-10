<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/5/2017
 * Time: 10:04 AM
 */
class Links_model extends CI_Model
{
    public function createLink($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_link(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);
        return $res;
    }
}