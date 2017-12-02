<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/1/2017
 * Time: 6:27 PM
 */
class Skill extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getCategories() {
        $result = array();

        $result['status'] = true;

        $res = $this->skill->getAllCategory();
        $result['data'] = $res;

        echo json_encode($result);
    }
}