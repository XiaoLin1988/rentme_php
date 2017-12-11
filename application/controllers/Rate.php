<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/11/2017
 * Time: 10:40 AM
 */
class Rate extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Rates_model', 'rate');
    }

    public function createRate() {
        $result = array();

        $data = array(
            'rt_type' => $_POST['type'],
            'rt_fid' => $_POST['foreign_id'],
            'rt_usr_id' => $_POST['user_id'],
            'rt_fl' => 1
        );

        $res = $this->rate->createRate($data);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

}