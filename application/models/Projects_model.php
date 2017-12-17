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

        if ($res) {
            return $this->db->insert_id();
        } else {
            return $res;
        }
    }

    public function createProject2($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }

        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_project(".$columns.") VALUES(".$values.")";
        $res = $this->db->query($query);

        if ($res) {
            return $this->db->insert_id();
        } else {
            return $res;
        }
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
        $res = $this->db->query(
            "SELECT
              sv.title as name,
              pr.pr_buyer as consumer_id,
              sv.talent_id as talent_id,
              (SELECT avatar FROM users u WHERE u.id=sv.talent_id) AS talent,
              (SELECT avatar FROM users u WHERE u.id=pr.pr_buyer) AS consumer
            FROM
              tbl_project pr, services sv
            WHERE
              pr.id={$projectid} AND sv.id=pr.pr_service")->result_array();

        return $res;
    }

    public function getMyProgressProjects($userid) {
        $res = $this->db->query(
            "SELECT
              sv.id as sv_id,
              sv.title as sv_title,
              sv.talent_id,
              pr.pr_buyer AS consumer_id,
              sv.skill_id,
              (SELECT img_path FROM tbl_img img WHERE img.img_type=3 AND img.img_fid=pr.pr_service LIMIT 0, 1) as sv_preview,
              sv.balance as sv_balance,
              sv.detail as sv_detail,
              pr.id as pr_id,
              pr.pr_stts,
              pr.pr_ctime
            FROM tbl_project pr, services sv WHERE (pr.pr_buyer={$userid} OR sv.talent_id={$userid}) AND pr.pr_service=sv.id AND pr.pr_stts=0")->result_array();

        return $res;
    }

    public function getMyCompletedProjects($userid) {
        $res = $this->db->query("SELECT
                  sv.id as sv_id,
                  sv.title as sv_title,
                  sv.talent_id,
                  pr.pr_buyer AS consumer_id,
                  sv.skill_id,
                  (SELECT img_path FROM tbl_img img WHERE img.img_type=3 AND img.img_fid=pr.pr_service LIMIT 0, 1) as sv_preview,
                  sv.balance as sv_balance,
                  sv.detail as sv_detail,
                  pr.id as pr_id,
                  pr.pr_stts,
                  pr.pr_ctime
                FROM tbl_project pr, services sv WHERE (pr.pr_buyer={$userid} OR sv.talent_id={$userid}) AND pr.pr_service=sv.id AND pr.pr_stts>0")->result_array();

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

    public function completeProject($id) {
        $res = $this->db->query("UPDATE tbl_project pr SET pr.pr_stts=1 WHERE id={$id}");
        return $res;
    }

    public function getProgressProjects($uid) {

    }

    public function getProjectReview($id) {

    }

    public function reviewProject($id) {
        $res = $this->db->query("UPDATE tbl_project SET pr_stts=2 WHERE id={$id}");
        return $res;
    }
}