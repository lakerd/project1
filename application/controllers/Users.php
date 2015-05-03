<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function index() {
        $user = $this->session->user;
        if ($user) {
            $view = $user->is_admin ? 'admin' : $user;
            $data['user'] = $user;
            $this->load->view('header');
            $this->load->view($view, $data);
            $this->load->view('footer');
            return;
        } 

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('header');
            $this->load->view('login');
            $this->load->view('footer');
            return;
        }
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $u = $this->users_model->login($username, $password);
        if ($u !== NULL) {
            $vals = array(
                'img_path'      => './captcha/',
                'img_url'       => 'http://example.com/captcha/'
            );

            $cap = create_captcha($vals);
            print_r($cap);
            $data = array(
                'captcha_time'  => $cap['time'],
                'ip_address'    => $this->input->ip_address(),
                'word'          => $cap['word']
            );

            $query = $this->db->insert_string('captcha', $data);
            $this->db->query($query);

            $this->session->set_userdata('user', $u);
            $data['user'] = $u;
            $data['cap'] = $cap;
            $view = $u->is_admin ? 'admin' : 'user';
            $this->load->view('header');
            $this->load->view($view, $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('msg', 'Невалидно потребителско име или парола.');
            $this->load->view('header');
            $this->load->view('login');
            $this->load->view('footer');
        }
    }

    public function edit($username) {
        $this->verify_admin();
        $user = $this->users_model->find_by_name($username);
        if ($user === NULL)
            show_404();
        $data['user'] = $user;
        $this->load->view('header');
        $this->load->view('edit_user', $data);
        $this->load->view('footer');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('/users/');
    }

    public function post_edit() {
        $this->verify_admin();
        $data = array(
            'id' => intval($this->input->post('id')),
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'username' => $this->input->post('username'),
            'is_admin' => intval($this->input->post('is_admin')),
            'occupation' => $this->input->post('occupation'),
            'city' => $this->input->post('city'),
            'employee' => $this->input->post('employee')
        );
        if (!$this->db->replace('users', $data)) {
            $this->session->flashdata('msg', 'Грешка! Потребителят не можа да бъде обновен.');
            redirect('/users/edit/' + $username);
        } else {
            $this->session->flashdata('msg', 'Потребителят беше обновен успешно.');
            redirect('/users/');
        }
    }

    public function create() {
        $this->verify_admin();
        $this->load->view('header');
        $this->load->view('create_user');
        $this->load->view('footer');
    }

    public function post_create() {
        $this->verify_admin();
        $this->form_validation->set_rules('first_name', 'First name', 'required');
        $this->form_validation->set_rules('last_name', 'Last name', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required|is_unique[users.username]');
        $this->form_validation->set_rules('is_admin', 'Is admin', 'required');
        $this->form_validation->set_rules('occupation', 'Occupation', 'required');
        $this->form_validation->set_rules('city', 'City', 'required');
        $this->form_validation->set_rules('employee', 'Employee', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->create();
        } else {
            $this->session->set_flashdata('msg', 'Потребителят беше създаден успешно.');
            redirect('/users/');
        }
    }

    public function post_search() {
        if (!$this->session->user)
            show_404();
        $this->form_validation->set_rules('egn', 'EGN', 'required');
        $this->form_validation->set_rules('egn', 'EGN', 'callback_egn_check');
        if ($this->form_validation->run() === FALSE) {
            $this->index();
            return;
        }
        $egn = $this->input->post('egn');
        $user_id = $this->session->user->id;
        $ip_addr = $this->input->ip_address();
        if (!$this->egn_model->egn_exists($egn)) {
            $msg = 'Това е първа зявка за ЕГН: ' . $egn;
            $color = 'green';
        } else { 
            $n = $this->egn_model->searched_in_3mo($egn);
            if ($n === 0) {
                $msg = 'В последните 3 месеца не е постъпвала заявка за ЕГН: ' . $egn;
                $color = 'green';
            } else {
                $msg = 'В последните 3 месеца са постъпили ' . $n . ' заявки за ЕГН: ' . $egn;
                $color = 'red';
            }
        }
        $data = array(
            'egn' => $egn,
            'user_id' => $user_id,
            'ip_addr' => $ip_addr,
        );
        $this->egn_model->add($data);
        $this->session->set_flashdata('msg', $msg);
        $this->session->set_flashdata('color', $color);
        redirect('/users/');
    }

######### private
    private function verify_admin() {
        $user = $this->session->user;
        if (!$user) {
            redirect('/users/');
            return;
        }
        if (!$user->is_admin)
            show_404();
    }

    public function egn_check($str) {
        if (egn_valid($str))
            return TRUE;
        $this->form_validation->set_message('egn_check', 'Невалидно ЕГН.');
        return FALSE;
    }
}
