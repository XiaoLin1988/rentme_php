<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 5/29/2017
 * Time: 12:28 AM
 */
class Api extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Users_model', 'user');
        $this->load->model('Projects_model', 'project');
        $this->load->model('Skills_model', 'skill');
        $this->load->model('Services_model', 'service');
        $this->load->model('Reviews_model', 'review');
        $this->load->model('Webs_model', 'web');
        $this->load->model('Videos_model', 'video');
        $this->load->model('Rates_model', 'rate');
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

        $sReviews = $this->review->getServiceReviews($serviceid, $curpage, PAGESIZE);
        /*
        foreach ($sReviews as $rv) {
            $rReviews = $this->review->getReviewReviews($rv['id']);
            $rv['reviews'] = $rReviews;

            array_push($data, $rv);
        }
        */
        $result['status'] = true;
        $result['data'] = $sReviews;

        echo json_encode($result);
    }

    public function getUserById() {
        $result = array();

        $res = $this->user->getUserById($_POST['userid']);

        if (sizeof($res) > 0) {
            $result['status'] = true;
            $result['data'] = $res;
        } else {
            $result['status'] = false;
            $result['data'] = "User does not exist.";
        }

        echo json_encode($result);
    }

    public function loginUser() {
        $result = array();

        $username = $_POST['name'];
        $userpassword = $_POST['password'];

        //$res = $this->user->getUserByName($username);
        $res = $this->user->getUserByEmail($username);

        if (!isset($res[0]['password'])) {
            $result['status'] = false;
            $result['data'] = "User is not existing. Please sign up.";
        } elseif ($res[0]['password'] === $userpassword) {
            $result['status'] = true;
            $result['data'] = $res[0];
        } else {
            $result['status'] = false;
            $result['data'] = "Account info is not correct.";
        }

        echo json_encode($result);
    }

    public function registerUser() {
        $result = array();

        $newuser = array(
            'name' => $_POST['name'],
            'type' => $_POST['type'],
            'email' => $_POST['email'],
            'password' => $_POST['password'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'latitude' => $_POST['latitude'],
            'longitude' => $_POST['longitude'],
            'zipcode' => $_POST['zipcode'],
            'workday' => $_POST['workday'],
            'worktime' => $_POST['worktime'],
            'rate' => $_POST['rate'],
            'skills' => $_POST['skills'],
            'avatar' => $_POST['avatar']
        );

        $res = $this->user->getUserByName($newuser['name']);
        if (sizeof($res) > 0) {
            $result['status'] = false;
            $result['data'] = "User name is already taken, try again with another name.";
        } else {
            $res = $this->user->getUserByPhone($newuser['phone']);
            if (sizeof($res) > 0) {
                $result['status'] = false;
                $result['data'] = "Phonenumber is already taken, try again with another name.";
            } else {
                $res = $this->user->registerUser($newuser);
                $result['status'] = true;
                $result['data'] = "Regiser User succeed.";
            }
        }

        echo json_encode($result);
    }

    public function getChattingProject() {
        $result = array();

        $projectid = $_POST['projectid'];

        $res = $this->project->getChattingProject($projectid);

        echo json_encode($res);

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

    public function forgotPassword() {
        $result = array();
        $email = $_POST['email'];
        $code = $_POST['code'];

        $res = $this->user->getUserByEmail($email);
        if (sizeof($res) > 0) {
            $user = array('password' => $code);
            $this->user->editUserProfile($user, $res[0]['id']);
            $ret = $this->sendEmail($email, $code);
        } else {
            $ret = false;
        }
        $result['status'] = $ret;
        echo json_encode($result);
    }

    public function searchByLocation() {
        $location = array(
            'latitude' => $_POST['latitude'],
            'longitude' => $_POST['longitude']
        );

        $skill = $_POST['skill'];

        $nearbies = $this->user->searchByLocation($location, RADIUS, $skill);

        echo json_encode($nearbies);
    }

    public function shareLocation() {
        $result = array();

        $data = array(
            "id" => $_POST['userid'],
            "latitude" => $_POST['latitude'],
            "longitude" => $_POST['longitude']
        );

        $this->user->shareLocation($data);

        $result['status'] = true;
        $result['data'] = "Successfully shared.";

        echo json_encode($result);
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

    public function completeProject() {
        $pr_id = $_POST['project_id'];
        $res = $this->project->completeProject($pr_id);

        $result = array();
        $result['status'] = $res;
        $result['data'] = $res;

        echo json_encode();
    }

    public function createProject2() {
        $result = array();

        $newproject = array(
            'pr_buyer' => $_POST['buyer_id'],
            'pr_service' => $_POST['service_id'],
            'pr_stts' => 0,
            'pr_df' => 0
        );

        $res = $this->project->createProject2($newproject);
        if (gettype($res) == "boolean") {
            $result['status'] = false;
            $result['data'] = 0;
        } else {
            $result['status'] = true;
            $result['data'] = $res;
        }

        echo json_encode($result);
    }

    public function createProject() {
        $result = array();

        $newproject = array(
            'name' => $_POST['title'],
            'description' => $_POST['description'],
            'status' => 0,
            'consumer_id' => $_POST['consumer_id'],
            'talent_id' => $_POST['talent_id'],
            'skill' => $_POST['skill'],
            'preview' => $_POST['preview']
        );

        $res = $this->project->createProject($newproject);
        if ($res == true) {
            $result['status'] = true;
            $result['data'] = "Creating new project succeed.";
        } else {
            $result['status'] = false;
            $result['data'] = "Creating new project failed.";
        }

        echo json_encode($result);
    }

    public function getMyProgressProjects() {
        
        $userid = $_POST['userid'];

        $res = $this->project->getMyProgressProjects($userid);

        echo json_encode($res);
    }

    public function getMyCompletedProjects() {
        $userid = $_POST['userid'];

        $res = $this->project->getMyCompletedProjects($userid);

        echo json_encode($res);
    }

    public function getMyReviews() {
        $userid = $_POST['userid'];

        $res = $this->project->getMyReviews($userid);

        echo json_encode($res);
    }

    public function leaveConsumerReview() {
        $result = array();

        $project = array(
            'id' => $_POST['projectid'],
            'consumer_score' => $_POST['consumer_score'],
            'consumer_review' => $_POST['consumer_review']
        );

        $res = $this->project->leaveConsumerReview($project);

        if($res == true) {
            $result['status'] = true;
            $result['data'] = "Leaving reviews successfully.";
        } else {
            $result['status'] = false;
            $result['data'] = "Leaving reviews failed.";
        }

        echo json_encode($result);
    }

    public function leaveTalentReview() {
        $result = array();

        $project = array(
            'id' => $_POST['projectid'],
            'talent_score' => $_POST['talent_score'],
            'talent_review' => $_POST['talent_review']
        );

        $res = $this->project->leaveTalentReview($project);

        if($res == true) {
            $result['status'] = true;
            $result['data'] = "Leaving reviews successfully.";
        } else {
            $result['status'] = false;
            $result['data'] = "Leaving reviews failed.";
        }

        echo json_encode($result);
    }

    public function getCategories() {
        $result = array();

        $result['status'] = true;

        $res = $this->skill->getAllCategory();
        $result['data'] = $res;

        echo json_encode($result);
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