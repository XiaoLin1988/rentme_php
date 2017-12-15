<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/9/2017
 * Time: 9:16 PM
 */
class Photos_model extends CI_Model
{

    public function createPhotos($data)
    {
        $columns = "";
        $values = "";
        foreach ($data as $key => $value) {
            $columns .= $key . ",";
            $values .= "'" . $value . "',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_img (" . $columns . ") VALUES(" . $values . ")";

        $res = $this->db->query($query);
        return $res;
    }

    public function getPhotos($type, $fid)
    {
        $res = $this->db->query("
            SELECT
              img_path
            FROM
              tbl_img
            WHERE
              img_type={$type} AND img_fid={$fid} AND img_df != 1
        ")->result_array();

        return $res;
    }
}