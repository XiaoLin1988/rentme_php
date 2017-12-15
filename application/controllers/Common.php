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
        $this->load->model('Photos_model', 'photo');

        $this->load->helper('url');
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

        echo json_encode($result);
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

        echo json_encode($result);
    }

    public function imageMultiUpload()
    {
        $result = array();

        $type = $_POST['type'];
        // 1 : profile main, 2 : profile sub, 3: service, 4: review
        $fid = $_POST['foreign_id'];

        $data = array();
        $root = 'uploads/';

        if (!isset($_FILES['images'])) {
            $result['status'] = false;
            $result['data'] = 'Please select image to upload';
        } else if (!isset($_POST['type']) or !isset($_POST['foreign_id'])) {
            $result['status'] = false;
            $result['data'] = 'Please select your image type';
        } else {
            if ($type == 1 or $type == 2) {
                $root .= 'profile/';
            } else if ($type == 3) {
                $root .= 'service/';
            } else if ($type == 4) {
                $root .= 'review/';
            }

            $upFiles = $_FILES['images'];

            for ($i = 0; $i < sizeof($upFiles['tmp_name']); $i++) {

                $file = $root . $this->createVerificationCode(20) . ".png";


                if (file_exists($file)) {
                    chmod($file, 0755);
                    unlink($file);
                }

                $ret = move_uploaded_file($upFiles['tmp_name'][$i], $file);

                if ($ret == TRUE) {

                    $file = base_url() . $file;
                    $key_value = array(
                        'img_path' => $file,
                        'img_type' => $type,
                        'img_fid' => $fid,
                        'img_ctime' => time(),
                        'img_utime' => time(),
                        'img_df' => 0
                    );
                    $ret = $this->photo->createPhotos($key_value);

                    if ($ret == TRUE) {
                        $d = array('test' => $file);
                        //array_push($data, $d);
                        array_push($data, $file);
                    }
                }
            }

            $result['status'] = true;
            $result['data'] = $data;
        }

        echo json_encode($result);

    }

    public function createVerificationCode($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
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