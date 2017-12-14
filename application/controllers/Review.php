<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/11/2017
 * Time: 9:52 AM
 */
class Review extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Reviews_model', 'review');
    }

    public function createReview() {
        $result = array();

        $data = array(
            'rv_type' => $_POST['type'],
            'rv_fid' => $_POST['foreign_id'],
            'rv_content' => $_POST['content'],
            'rv_score' => $_POST['score'],
            'rv_usr_id' => $_POST['user_id']
        );

        $res = $this->review->createReview($data);

        if (gettype($res) == "boolean") {
            $result['status'] = false;
            $result['data'] = 0;
        } else {
            $result['status'] = true;
            $result['data'] = $res;
        }

        echo json_encode($result);
    }


    public function getReviewReviews() {
        $result = array();
        $data = array();

        $userid = $_POST['user_id'];

        $rReviews = $this->review->getReviewReviews($_POST['review_id']);
        foreach ($rReviews as $review) {
            $rateCnt = $this->rate->getRateCount($review['id']);
            $reviewCnt = $this->review->getReviewReviewCount($review['id']);
            $rated = $this->rate->checkRated($review['id'], $userid);

            $review['rated'] = $rated;
            $review['rate_count'] = $rateCnt;
            $review['review_count'] = $reviewCnt;
            $review['web_links'] = $this->web->getWebLinks(1, $review['id']);
            $review['videos'] = $this->video->getVideoLinks(1, $review['id']);

            array_push($data, $review);
        }

        $result['status'] = true;
        $result['data'] = $data;

        echo json_encode($result);
    }
}