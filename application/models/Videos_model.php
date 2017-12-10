<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/9/2017
 * Time: 9:16 PM
 */
class Videos_model extends CI_Model {

    public function createVideo($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_video(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);
        return $res;
    }
}