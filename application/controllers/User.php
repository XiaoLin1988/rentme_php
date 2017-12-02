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
                $this->user->registerUser($newuser);
                $result['status'] = true;
                $result['data'] = "Regiser User succeed.";
            }
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
            $result['data'] = $res;
        } else {
            $result['status'] = false;
            $result['data'] = "Account info is not correct.";
        }

        echo json_encode($result);
    }

    public function editUserProfile() {
        $result = array();

        $newuser = array(
            'name' => $_POST['name'],
            'phone' => $_POST['phone'],
            'address' => $_POST['address'],
            'latitude' => $_POST['latitude'],
            'longitude' => $_POST['longitude'],
            'zipcode' => $_POST['zipcode'],
            'workday' => $_POST['workday'],
            'worktime' => $_POST['worktime'],
            'rate' => $_POST['rate'],
            'password' => $_POST['password'],
            'skills' => $_POST['skills'],
            'avatar' => $_POST['avatar']
        );

        $id = $_POST['id'];

        $this->user->editUserProfile($newuser, $id);
        $result['status'] = true;
        $result['data'] = "Edit profile succeed.";

        echo json_encode($result);
    }
}