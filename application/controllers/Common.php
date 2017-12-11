<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/5/2017
 * Time: 10:03 AM
 */
class Common extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Webs_model', 'web');
        $this->load->model('Videos_model', 'video');
    }

    public function uploadImage() {
        $image = $_POST['image'];
        $name = $_POST['name'];

        $ifp = fopen( '/var/www/html/uploads/'.$name, 'wb');
        fwrite( $ifp, base64_decode( $image ) );
        fclose( $ifp );

        echo $name;
    }

    public function uploadImageF($image, $name) {
        $ifp = fopen( '/var/www/html/uploads/'.$name, 'wb' );
        fwrite( $ifp, base64_decode( $image ) );
        fclose( $ifp );

        return '/var/www/html/uploads/'.$name;
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

    public function sendEmail($email, $code) {
        ini_set('display_errors',1);

        $to = $email;
        $subject = "Hello. Here is JCChat.";
        $message = "Your temp password.\n\n"
            ."code : \n"
            .$code."\n\n";
        $from = "RentME";
        $headers = "Mime-Version:1.0\n";
        $headers .= "Content-Type : text/html;charset=UTF-8\n";
        $headers .= "From:" . $from;

        $res = mail($to, $subject, $message, $headers);

        return $res;
    }
}