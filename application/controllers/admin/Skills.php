<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 6/24/2017
 * Time: 4:32 PM
 */
class Skills extends Admin_Controller {
    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->lang->load('admin/skills');

        /* Title Page :: Common */
        $this->page_title->push(lang('menu_skills'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_skills'), 'admin/skills');

        $this->load->model('Skills_model', 'skill');

        $this->load->helper('url');

        $this->load->library('image_CRUD');
    }

    /*
    public function index() {
        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            $this->data['skills'] = $this->skill->getAllCategory();

            //$this->template->admin_render('admin/skills/index', $this->data);

            $image_crud = new image_CRUD();

            $image_crud->set_primary_key_field('id');
            $image_crud->set_url_field('preview');
            $image_crud->set_title_field('title');
            $image_crud->set_table('skills')->set_image_path('uploads/service');

            $output = $image_crud->render();

            $this->data['output'] = $output;

            $this->template->admin_render('admin/skills/index', $this->data);
        }
    }
    */

    public function index() {
        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            $this->data['skills'] = $this->skill->getAllCategory();

            $this->template->admin_render('admin/skills/index', $this->data);
        }
    }

    public function uploadPreview() {
        $result = array();

        $targetDir = "uploads/";
        $fileName = time();
        $targetFile = $targetDir.$fileName;
        if(move_uploaded_file($_FILES['image']['tmp_name'],$targetFile)){
            $result['status'] = true;
            $result['data'] = base_url('uploads/'.$fileName.'.png');
        } else {
            $result['status'] = false;
        }

        echo json_encode($result);
    }

    public function create()
    {
        /* Breadcrumbs */
        $this->breadcrumbs->unshift(2, lang('menu_skills_create'), 'admin/skills/create');
        $this->data['breadcrumb'] = $this->breadcrumbs->show();

        /* Validate form input */
        $this->form_validation->set_rules('preview', 'lang:create_skill_preview', 'required');
        $this->form_validation->set_rules('title', 'lang:create_skill_title', 'required');

        if ($this->form_validation->run() == TRUE)
        {
            $title = $this->input->post('title');
            $preview = $this->input->post('preview');

            $skill = array(
                'title' => $title,
                'preview' => $preview
            );

            $this->skill->createCategory($skill);

            redirect('admin/skills', 'refresh');
        } else {
            $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

            $this->data['title'] = array(
                'name'  => 'title',
                'id'    => 'title',
                'type'  => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('title'),
            );

            array_push($this->data['js_files'], base_url('/assets/dropzone/dropzone.js'));
            array_push($this->data['css_files'], base_url('/assets/dropzone/dropzone.css'));

            /* Load Template */
            $this->template->admin_render('admin/skills/create', $this->data);
        }
    }
}