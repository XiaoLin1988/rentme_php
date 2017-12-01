<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 6/22/2017
 * Time: 3:27 PM
 */
class Projects extends Admin_Controller {
    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->lang->load('admin/projects');

        /* Title Page :: Common */
        $this->page_title->push(lang('menu_projects'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_projects'), 'admin/projects');

        $this->load->model('Projects_model', 'project');
    }

    public function index()
    {
        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            /* Get all users */
            $this->data['projects'] = $this->project->getProjects();

            /* Load Template */
            $this->template->admin_render('admin/projects/index', $this->data);
        }
    }

    public function edit($id)
    {
        $id = (int) $id;

        if ( ! $this->ion_auth->logged_in() OR ( ! $this->ion_auth->is_admin() ))
        {
            redirect('auth', 'refresh');
        }

        $this->breadcrumbs->unshift(2, lang('menu_projects_edit'), 'admin/projects/edit');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        $project = $this->project->getProjectById($id);

        $this->form_validation->set_rules('name', 'lang:edit_project_validation_name_label', 'required');
        $this->form_validation->set_rules('description', 'lang:edit_project_validation_description_label', 'required');
        $this->form_validation->set_rules('timeframe', 'lang:edit_project_validation_timeframe_label', 'required');
        $this->form_validation->set_rules('consumer_score', 'lang:edit_project_validation_cscore_label', 'required');
        $this->form_validation->set_rules('consumer_review', 'lang:edit_project_validation_creview_label', 'required');
        $this->form_validation->set_rules('talent_score', 'lang:edit_project_validation_tscore_label', 'required');
        $this->form_validation->set_rules('talent_review', 'lang:edit_project_validation_treview_label', 'required');
        //$this->form_validation->set_rules('status', 'lang:edit_project_validation_status_label', 'required');

        if (isset($_POST) && ! empty($_POST))
        {
            if ($this->form_validation->run() == TRUE)
            {
                $data = array(
                    'name'              => $this->input->post('name'),
                    'description'       => $this->input->post('description'),
                    'timeframe'         => $this->input->post('timeframe'),
                    'consumer_score'    => $this->input->post('consumer_score'),
                    'consumer_review'   => $this->input->post('consumer_review'),
                    'talent_score'      => $this->input->post('talent_score'),
                    'talent_review'     => $this->input->post('talent_review'),
                    //'status'            => $this->input->post('status')
                );

                if($this->project->updateProject($project->id, $data))
                {
                    $this->session->set_flashdata('message', $this->ion_auth->messages());

                    if ($this->ion_auth->is_admin())
                    {
                        redirect('admin/projects', 'refresh');
                    }
                    else
                    {
                        redirect('admin', 'refresh');
                    }
                }
                else
                {
                    $this->session->set_flashdata('message', $this->ion_auth->errors());

                    if ($this->ion_auth->is_admin())
                    {
                        redirect('auth', 'refresh');
                    }
                    else
                    {
                        redirect('/', 'refresh');
                    }
                }
            }
        }

        // display the edit user form
        //$this->data['csrf'] = $this->_get_csrf_nonce();

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['project'] = $project;

        $this->data['name'] = array(
            'name' => 'name',
            'id'   => 'name',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('name', $project->name)
        );

        $this->data['description'] = array(
            'name' => 'description',
            'id'   => 'description',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('description', $project->description)
        );

        $this->data['timeframe'] = array(
            'name' => 'timeframe',
            'id'   => 'timeframe',
            'type' => 'number',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('timeframe', $project->timeframe)
        );

        $this->data['consumer_score'] = array(
            'name' => 'consumer_score',
            'id'   => 'consumer_score',
            'type' => 'number',
            'max'  => '5',
            'min'  => '0',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('consumer_score', $project->consumer_score)
        );

        $this->data['consumer_review'] = array(
            'name' => 'consumer_review',
            'id'   => 'consumer_review',
            'type' => 'text',
            'class' => 'form-control',
            'rows' => '4',
            'value' => $this->form_validation->set_value('consumer_review', $project->consumer_review)
        );

        $this->data['talent_score'] = array(
            'name' => 'talent_score',
            'id'   => 'talent_score',
            'type' => 'number',
            'max'  => '5',
            'min'  => '0',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('talent_score', $project->talent_score)
        );

        $this->data['talent_review'] = array(
            'name' => 'talent_review',
            'id'   => 'talent_review',
            'type' => 'text',
            'rows' => '4',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('talent_review', $project->talent_review)
        );

        $this->data['status'] = array(
            'name' => 'status',
            'id'   => 'status',
            'type' => 'text',
            'class' => 'form-control',
            'value' => $this->form_validation->set_value('status', $project->status)
        );
        $this->template->admin_render('admin/projects/edit', $this->data);
    }

    public function detail($id)
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_projects_detail'), 'admin/projects/detail');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Data */
        $id = (int) $id;

        $this->data['project_info'] = $this->project->getProjectById($id);

        /* Load Template */
        $this->template->admin_render('admin/projects/detail', $this->data);
    }
}