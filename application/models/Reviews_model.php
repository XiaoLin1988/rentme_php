<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/6/2017
 * Time: 1:22 AM
 */
class Reviews_model extends CI_Model
{
    public function createReview($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }

        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO tbl_review(".$columns.", rv_ctime) VALUES(".$values.", NOW())";
        $res = $this->db->query($query);

        if ($res) {
            return $this->db->insert_id();
        } else {
            return $res;
        }
    }

    public function getProejctReview($projectid) {
        $res = $this->db->query(
            "SELECT
              rv.*,
              (SELECT us.avatar FROM users us WHERE us.id=rv.rv_usr_id) AS user_avatar,
              (SELECT us.name FROM users us WHERE us.id=rv.rv_usr_id) as user_name
            FROM
              tbl_review rv, tbl_project pr
            WHERE
              rv.rv_type=0 AND rv.rv_fid=pr.pr_service AND pr.pr_stts=2 AND pr.id={$projectid}"
        /*LIMIT {$pos}, {$pagesize}"*/)->result_array();
        return $res;
    }

    public function getServiceReviews($serviceid, $curpage, $pagesize) {
        $pos = $curpage * $pagesize;
        $res = $this->db->query(
            "SELECT
              rv.*,
              /*(SELECT img.img_path FROM tbl_img img WHERE img.img_type=1 AND img.img_fid=rv.rv_usr_id) AS user_avatar,*/
              (SELECT us.avatar FROM users us WHERE us.id=rv.rv_usr_id) AS user_avatar,
              (SELECT us.name FROM users us WHERE us.id=rv.rv_usr_id) as user_name
            FROM
              tbl_review rv
            WHERE
              rv.rv_type=0 AND rv.rv_fid={$serviceid}"
            /*LIMIT {$pos}, {$pagesize}"*/)->result_array();
        return $res;
    }

    public function getServiceReviewCount($serviceid) {
        $res = $this->db->query("
            SELECT
                COUNT(*) AS cnt
            FROM
              tbl_review
            WHERE
              rv_type=0 AND rv_fid={$serviceid}
        ")->result_array();
        return $res[0]['cnt'];
    }

    public function getReviewReviewCount($reviewid) {
        $res = $this->db->query("
            SELECT
                COUNT(*) AS cnt
            FROM
              tbl_review
            WHERE
              rv_type=1 AND rv_fid={$reviewid}
        ")->result_array();
        return $res[0]['cnt'];
    }

    public function getReviewReviews($reviewid) {
        $res = $this->db->query(
            "SELECT rv.*,
              /*(SELECT img.img_path FROM tbl_img img WHERE img.img_type=1 AND img.img_fid=rv.rv_usr_id) AS user_avatar,*/
              (SELECT us.avatar FROM users us WHERE us.id=rv.rv_usr_id) AS user_avatar,
              (SELECT us.name FROM users us WHERE us.id=rv.rv_usr_id) as user_name
            FROM
              tbl_review rv
            WHERE
              rv.rv_type=1 AND rv.rv_fid={$reviewid}")->result_array();

        return $res;
    }
}