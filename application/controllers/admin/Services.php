<?php

/**
 * Created by PhpStorm.
 * User: emerald
 * Date: 6/24/2017
 * Time: 3:46 PM
 */
class Services extends Admin_Controller {
    public function __construct()
    {
        parent::__construct();

        /* Load :: Common */
        $this->lang->load('admin/services');

        /* Title Page :: Common */
        $this->page_title->push(lang('menu_services'));
        $this->data['pagetitle'] = $this->page_title->show();

        /* Breadcrumbs :: Common */
        $this->breadcrumbs->unshift(1, lang('menu_services'), 'admin/services');

        $this->load->model('Services_model', 'service');
    }

    public function index() {
        if ( ! $this->ion_auth->logged_in() OR ! $this->ion_auth->is_admin())
        {
            redirect('auth/login', 'refresh');
        }
        else
        {
            /* Breadcrumbs */
            $this->data['breadcrumb'] = $this->breadcrumbs->show();

            $this->data['services'] = $this->service->getServices();

            $this->template->admin_render('admin/services/index', $this->data);
        }
    }

}