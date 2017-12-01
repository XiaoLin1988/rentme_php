<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 5/29/2017
 * Time: 10:27 AM
 */
class Projects_model extends CI_Model {
    public function createProject($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }

        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO projects(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);
        return $res;
    }

    public function getProjectById($id) {
        $res = $this->db->query(
            "SELECT id, name, description, timeframe,
                (SELECT name FROM users u WHERE u.id=p.consumer_id) AS consumer,
                (SELECT name FROM users u WHERE u.id=p.talent_id) AS talent,
                consumer_score, consumer_review, talent_score, talent_review,
                status, skill, preview
            FROM projects p"
        )->row();

        return $res;
    }

    public function getProjects() {
        $res = $this->db->query(
            "SELECT id, name, description, timeframe,
                (SELECT name FROM users u WHERE u.id=p.consumer_id) AS consumer,
                (SELECT name FROM users u WHERE u.id=p.talent_id) AS talent,
                consumer_score, consumer_review, talent_score, talent_review,
                status, skill, preview
            FROM projects p")->result_array();

        return $res;
    }

    public function getChattingProject($projectid) {
        $res = $this->db->query("SELECT name, consumer_id, talent_id, (SELECT avatar FROM users u WHERE u.id=p.consumer_id) AS consumer, (SELECT avatar FROM users u WHERE u.id=p.talent_id) AS talent FROM projects p WHERE id=$projectid")->result_array();

        return $res;
    }

    public function getMyProgressProjects($userid) {
        $res = $this->db->query("SELECT * FROM projects WHERE (consumer_id={$userid} OR talent_id={$userid}) AND status=0")->result_array();

        return $res;
    }

    public function getMyCompletedProjects($userid) {
        $res = $this->db->query("SELECT * FROM projects WHERE (consumer_id={$userid} OR talent_id={$userid}) AND status=1")->result_array();

        return $res;
    }

    public function getMyReviews($userid) {
        $res = $this->db->query("SELECT * FROM projects WHERE (consumer_id={$userid} OR talent_id={$userid}) AND status=1 AND consumer_score!=0 AND talent_score!=0")->result_array();

        return $res;
    }

    public function leaveConsumerReview($project) {
        $res = $this->db->query("UPDATE projects SET consumer_score='".$project['consumer_score']."', consumer_review='".$project['consumer_review']."' WHERE id={$project['id']}");

        return $res;
    }

    public function leaveTalentReview($project) {
        $res = $this->db->query("UPDATE projects SET talent_score='".$project['talent_score']."', talent_review='".$project['talent_review']."' WHERE id={$project['id']}");

        return $res;
    }

    public function updateProject($id, $data) {
        $sets = "";
        foreach ($data as $key=>$value) {
            $sets .= $key.'="'.$value.'",';
        }

        $sets = rtrim($sets, ",");

        $res = $this->db->query("UPDATE projects SET ".$sets." WHERE id={$id}");

        return $res;
    }
}