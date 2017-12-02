<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/1/2017
 * Time: 4:46 PM
 */
class Service extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Services_model', 'service');
    }

    public function createService() {
        $result = array();

        $newservice = array(
            'talent_id' => $_POST['talent_id'],
            'skill_id' => $_POST['skill_id'],
            'preview' => isset($_POST['preview']) ? $_POST['preview'] : '',
            'title' => $_POST['title'],
            'balance' => $_POST['balance'],
            'detail' => $_POST['detail']
        );

        $res = $this->service->createService($newservice);
        if ($res == true) {
            $result['status'] = true;
            $result['data'] = "Creating new service succeed.";
        } else {
            $result['status'] = false;
            $result['data'] = "Creating new service failed.";
        }

        echo json_encode($result);
    }

    public function getUserServices() {
        $result = array();

        $talentid = $_POST['talentid'];

        $res = $this->service->getUserServices($talentid);

        $result['status'] = true;
        $result['data'] = $res;
        echo json_encode($result);
    }

    public function getServiceReviews() {
        $result = array();

        $serviceid = $_POST['service_id'];

        $res = $this->service->getServiceReviews($serviceid);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }
}