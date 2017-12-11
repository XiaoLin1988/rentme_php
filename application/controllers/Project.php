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

    public function completeProject() {
        $pr_id = $_POST['project_id'];
        $res = $this->project->completeProject($pr_id);

        $result = array();
        $result['status'] = $res;
        $result['data'] = $res;

        echo json_encode($result);
    }

    public function getChattingProject() {
        $result = array();

        $projectid = $_POST['projectid'];

        $res = $this->project->getChattingProject($projectid);

        echo json_encode($res);

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

        $result = array();
        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
    }

    public function getMyCompletedProjects() {
        $userid = $_POST['userid'];

        $res = $this->project->getMyCompletedProjects($userid);

        $result = array();
        $result['status'] = true;
        $result['data'] = $res;

        echo json_encode($result);
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
}