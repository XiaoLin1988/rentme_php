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
        return $res;
    }

    public function getUserServices($talentid) {
        $res = $this->db->query(
            "SELECT sv.id, sv.title, sv.talent_id, sv.skill_id, sk.title AS skill, sv.preview, sv.detail, sv.balance, COUNT(pr.id) AS review_cnt, AVG(pr.talent_score) AS review_score FROM users u, services sv, skills sk, projects pr WHERE sv.talent_id={$talentid} AND u.id=sv.talent_id AND sk.id=sv.skill_id AND pr.service_id=sv.id"
        )->result_array();

        return $res;
    }

    public function getServiceReviews($serviceid) {
        $res = $this->db->query("
                SELECT * FROM projects WHERE service_id={$serviceid}
            ")->result_array();

        return $res;
    }

    public function getServices() {
        $res = $this->db->query(
            "SELECT id, (SELECT name FROM users u WHERE u.id=sv.talent_id) AS talent,
                (SELECT title FROM skills sk WHERE sk.id=sv.skill_id) AS skill,
                preview, balance, detail
            FROM services sv"
        )->result_array();

        return $res;
    }

}