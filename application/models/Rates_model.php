<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/10/2017
 * Time: 4:02 PM
 */
class Rates_model extends CI_Model
{
    public function createRate($data) {
        $query = "SELECT * FROM tbl_rate WHERE rt_type={$data['rt_type']} AND rt_fid={$data['rt_fid']} AND rt_usr_id={$data['rt_usr_id']}";
        $res = $this->db->query($query)->result_array();
        if (sizeof($res) > 0) {
            if ($res[0]['rt_fl'] == 0) { // already rated, so you are going to unrate
                $this->db->query("UPDATE tbl_rate SET rt_fl=1 WHERE rt_type={$data['rt_type']} AND rt_fid={$data['rt_fid']} AND rt_usr_id={$data['rt_usr_id']}");
                return 1;
            } else {
                $this->db->query("UPDATE tbl_rate SET rt_fl=0 WHERE rt_type={$data['rt_type']} AND rt_fid={$data['rt_fid']} AND rt_usr_id={$data['rt_usr_id']}");
                return 0;
            }
        } else {
            $columns = "";
            $values = "";
            foreach ($data as $key=>$value) {
                $columns .= $key.",";
                $values .= "'".$value."',";
            }

            $columns = rtrim($columns, ",");
            $values = rtrim($values, ",");

            $query = "INSERT INTO tbl_rate(".$columns.", rt_ctime) VALUES(".$values.", NOW())";
            $this->db->query($query);
            return 1;
        }
    }
}