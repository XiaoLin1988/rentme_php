<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/11/2017
 * Time: 10:38 AM
 */
class WebLink extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Webs_model', 'web');
    }

    public function uploadWebs() {
        $result = array();

        $type = $_POST['type'];
        $fid = $_POST['foreign_id'];

        $webs = json_decode($_POST['web']);

        foreach ($webs as $web) {
            $data = array(
                'web_type' => $type,
                'web_fid' => $fid,
                'web_title' => $web->title,
                'web_content' => $web->content,
                'web_image' => $web->thumbnail,
                'web_link' => $web->link,
                'web_df' => 0
            );

            $this->web->createWeb($data);
        }

        $result['status'] = true;
        $result['data'] = 'success';
    }

}