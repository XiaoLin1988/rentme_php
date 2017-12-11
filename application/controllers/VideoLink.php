<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/11/2017
 * Time: 10:39 AM
 */
class VideoLink extends CI_Controller
{
    public function __construct() {
        parent::__construct();
        $this->load->model('Videos_model', 'video');
    }

    public function uploadVideos() {
        $result = array();

        $type = $_POST['type'];
        $fid = $_POST['foreign_id'];

        $videos = $_POST['video'];

        foreach ($videos as $video) {
            $data = array(
                'vd_type' => $type,
                'vd_fid' => $fid,
                'vd_url' => $video,
                'vd_df' => 0
            );

            $this->video->createVideo($data);
        }

        $result['status'] = true;
        $result['data'] = 'success';
    }

}