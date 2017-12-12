<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 5/29/2017
 * Time: 12:29 AM
 */
class Users_model extends CI_Model {

    public function registerUser($data)
    {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }
        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO users(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);
        return $res;
    }

    public function getUserByName($name) {
        $data = $this->db->query("SELECT * FROM users WHERE name LIKE '{$name}'")->result_array();
        return $data;
    }

    public function getUserByEmail($name) {
        $data = $this->db->query("SELECT * FROM users WHERE email IS NOT NULL AND email LIKE '{$name}'")->result_array();
        return $data;
    }    

    public function getUserById($id) {
        $data = $this->db->query("SELECT * FROM users WHERE id={$id}")->result_array();
        return $data;
    }

    public function getUserByPhone($phone) {
        $data = $this->db->query("SELECT * FROM users WHERE phone LIKE '{$phone}'")->result_array();
        return $data;
    }

    public function searchByLocation($location, $radius, $skill) {
        $data = $this->db->query(
            "SELECT *, round((
				6371 * acos (
				cos ( radians({$location['latitude']}) )
				* cos( radians( latitude ) )
				* cos( radians( longitude ) - radians({$location['longitude']}) )
				+ sin ( radians({$location['latitude']}) )
				* sin( radians( latitude ) )
				)
            ),1) AS distance
            FROM users WHERE( (NOT longitude = {$location['longitude']}) AND ( NOT latitude = {$location['latitude']}) )
            HAVING distance < {$radius} AND skills LIKE '%{$skill}%'
            ORDER BY distance
            LIMIT 0 , 50;")->result_array();

        return $data;
    }

    public function editUserProfile($user, $id) {
        $sets = "";
        foreach ($user as $key=>$value) {
            $sets .= $key."='".$value."',";
        }

        $sets = rtrim($sets, ",");

        $res = $this->db->query("UPDATE users SET ".$sets." WHERE id={$id}");

        return $res;
    }

    public function shareLocation($data) {
        $res = $this->db->query("UPDATE users SET latitude={$data['latitude']}, longitude={$data['longitude']} WHERE id={$data['id']}");

        return $res;
    }

    public function getByGoogle($gaccount) {
        $query = "SELECT * FROM users WHERE ggId='{$gaccount}'";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }

    public function getByFacebook($fbaccount) {
        $query = "SELECT * FROM users WHERE fbId='{$fbaccount}'";

        $ret = $this->db->query($query)->result_array();

        return $ret;
    }





}