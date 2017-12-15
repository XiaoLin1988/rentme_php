<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 6/24/2017
 * Time: 3:48 PM
 */
class Services_model extends CI_Model {

    public function createService ($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= '"'.$value.'",';
        }

        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO services(".$columns.") VALUES(".$values.")";
        $res = $this->db->query($query);

        if ($res) {
            return $this->db->insert_id();
        } else {
            return $res;
        }
    }

    public function getUserServices($talentid) {
        $res = $this->db->query(
            "SELECT
              sv.*,
              (SELECT count(*) FROM tbl_review rv WHERE rv.rv_usr_id=sv.talent_id AND rv.rv_fid=sv.id AND rv.rv_type=0) AS review_cnt,
              (SELECT avg(rv.rv_score) FROM tbl_review rv WHERE rv.rv_usr_id=sv.talent_id AND rv.rv_fid=sv.id AND rv.rv_type=0) AS review_score,
              sk.title AS skill_title, sk.preview AS skill_preview
            FROM
              services sv, skills sk
            WHERE
              sv.talent_id={$talentid} AND sk.id=sv.skill_id AND sv.df=0"
        )->result_array();

        return $res;
    }

    public function getServices() {
        $res = $this->db->query(
            "SELECT id, (SELECT name FROM users u WHERE u.id=sv.talent_id) AS talent,
                (SELECT title FROM skills sk WHERE sk.id=sv.skill_id) AS skill,
                preview, balance, detail
            FROM services sv WHERE sv.df=0"
        )->result_array();

        return $res;
    }

    public function deleteservice($service_id) {
        $res = $this->db->query("UPDATE services SET df=1 WHERE id={$service_id}");

        return $res;
    }

}