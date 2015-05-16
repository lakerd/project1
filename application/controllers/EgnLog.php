<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EgnLog extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }

    public function index($page = 0) {
        $user = $this->session->user;
        if (!$user) {
            redirect('/users/');
            return;
        }
        if (!$user->is_admin)
            show_404();

        $per_page = 20;
        $logs = $this->egn_model->all($page, $per_page);

        $c['base_url'] = base_url() . '/log/';
        $c['total_rows'] = $this->egn_model->total_count();
        $c['per_page'] = $per_page;

        $this->load->library('pagination');
        $this->pagination->initialize($c);

        $data = array();
        $data['pagination'] = $this->pagination->create_links();
        $data['logs'] = $logs;

        $this->load->view('header');
        $this->load->view('egn_log', $data);
        $this->load->view('footer');
    }
}

