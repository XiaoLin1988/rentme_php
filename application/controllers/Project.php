<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 12/1/2017
 * Time: 4:49 PM
 */
class Project extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Projects_model', 'project');
    }

    public function createProject() {
        $result = array();

        $newproject = array(
            'pr_buyer' => $_POST['title'],
            'pr_service' => $_POST['description'],
            'pr_stts' => 0,
            'pr_df' => $_POST['preview']
        );

        $res = $this->project->createProject($newproject);
        if (gettype($res) == "boolean") {
            $result['status'] = false;
            $result['data'] = 0;
        } else {
            $result['status'] = true;
            $result['data'] = $res;
        }

        echo json_encode($result);
    }

    public function getProgressProjects() {
        $result = array();

        $res = $this->project->getProgressProjects($_POST['user_id']);

        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }
}