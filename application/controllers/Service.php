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
        $this->load->model('Rates_model', 'rate');
        $this->load->model('Reviews_model', 'review');
    }

    public function createServiceIos() {
        $result = array();

        $preview = $this->uploadImageF($_POST['preview'], $_POST['name']);

        $newservice = array(
            'talent_id' => $_POST['talent_id'],
            'skill_id' => $_POST['skill_id'],
            'preview' => $preview,
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

    public function createService() {
        $result = array();

        $newservice = array(
            'talent_id' => $_POST['talent_id'],
            'skill_id' => $_POST['skill_id'],
            'title' => $_POST['title'],
            'balance' => $_POST['balance'],
            'detail' => $_POST['detail']
        );

        $res = $this->service->createService($newservice);
        if (gettype($res) == "boolean") {
            $result['status'] = false;
            $result['data'] = 0;
        } else {
            $result['status'] = true;
            $result['data'] = $res;
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
        $data = array();

        $curpage = 0;
        if (isset($_POST['curpage'])) {
            $curpage = $_POST['curpage'];
        }
        $serviceid = $_POST['service_id'];
        $userid = $_POST['user_id'];

        $sReviews = $this->review->getServiceReviews($serviceid, $curpage, PAGESIZE);
        foreach ($sReviews as $review) {
            $rateCnt = $this->rate->getRateCount($review['id']);
            $reviewCnt = $this->review->getReviewReviewCount($review['id']);
            $rated = $this->rate->checkRated($review['id'], $userid);

            $review['rated'] = $rated;
            $review['rate_count'] = $rateCnt;
            $review['review_count'] = $reviewCnt;

            array_push($data, $review);
        }

        $result['status'] = true;
        $result['data'] = $data;

        echo json_encode($result);
    }
}