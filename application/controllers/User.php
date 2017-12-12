<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/1/2017
 * Time: 4:40 PM
 */
class User extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Users_model', 'user');
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

    public function getUserServices() {
        $result = array();

        $talentid = $_POST['talentid'];

        $res = $this->service->getUserServices($talentid);

        $result['status'] = true;
        $result['data'] = $res;
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


}