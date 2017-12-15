<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 5/29/2017
 * Time: 12:21 PM
 */
class Skills_model extends CI_Model {

    public function getAllCategory() {
        $res = $this->db->query("SELECT id, title, preview AS path FROM skills")->result_array();

        return $res;
    }

    public function createCategory($data) {
        $columns = "";
        $values = "";
        foreach ($data as $key=>$value) {
            $columns .= $key.",";
            $values .= "'".$value."',";
        }

        $columns = rtrim($columns, ",");
        $values = rtrim($values, ",");

        $query = "INSERT INTO skills(".$columns.") VALUES(".$values.")";

        $res = $this->db->query($query);
        return $res;
    }

    public function addCategory($cat) {
        $res = $this->db->query("INSERT INTO skills('name') VALUES('$cat'); ");

        return $res;
    }

    public function updateCategory($new, $old) {
        $res = $this->db->query("UPDATE skills SET name='{$new}' WHERE name LIKE '{$old}'");

        return $res;
    }

    public function deleteCategory($cat) {
        $res = $this->db->query("DELETE FROM skills WHERE  name LIKE '$cat'");

        return $res;
    }

}