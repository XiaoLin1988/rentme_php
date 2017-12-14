<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/9/2017
 * Time: 9:15 PM
 */
class Webs_model extends CI_Model {

    public function createWeb($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_web(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);
        return $res;
    }

    public function getWebLinks($type, $fid) {
        $res = $this->db->query("
            SELECT
              web_title AS title,
              web_content AS content,
              web_image AS thumbnail,
              web_link AS link
            FROM
              tbl_web
            WHERE
              web_type={$type} AND web_fid={$fid}
        ")->result_array();

        return $res;
    }
}