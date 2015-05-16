<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('users_model');
    }

    public function index() {
        if ($this->session->user) {
            $user = $this->session->user;
            $cap = $this->create_captcha();
            $data['user'] = $user;
            $data['cap_img'] = $cap['image'];
            $this->load->view('header');
            $this->load->view('user', $data);
            if ($user->is_admin) {
                $egn = $this->session->userdata('egn');
                if (isset($egn)) {
                    $logs = $this->egn_model->find($egn);
                    $this->load->view('egn_log', array('logs' => $logs));
                }
            }
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
        $user = $this->users_model->login($username, $password);
        if ($user !== NULL) {
            $cap = $this->create_captcha();
            $this->session->set_userdata('user', $user);
            $data['user'] = $user;
            $data['cap_img'] = $cap['image'];
            $this->load->view('header');
            $this->load->view('user', $data);
            $this->load->view('footer');
        } else {
            $this->session->set_flashdata('msg', 'Невалидно потребителско име или парола.');
            $this->load->view('header');
            $this->load->view('login');
            $this->load->view('footer');
        }
    }

    public function edit() {
        $this->verify_admin();
        $this->load->view('header');
        $this->load->view('edit_user');
        $this->load->view('footer');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('/users/');
    }

    public function post_edit() {
        $this->verify_admin();
        $form = $this->input->post('form');
        if (!$form)
            show_404();
        if ($form === 'search') {
            $this->post_edit_search();
        } else if ($form === 'edit') {
            $this->post_edit_update();
        } else {
            throw new Exception("unknown form");
        }
    }

    public function post_delete() {
        $this->verify_admin();
        $id = $this->input->post('id');
        $success = $this->users_model->delete_user($id);
        $msg = $success ? 'Потребителят беше изтрит успешно.' : 'Грешка при изтриването!';
        $this->session->set_flashdata('msg', $msg);
        redirect('/users/edit');
    }

    private function post_edit_search() {
        $this->verify_admin();
        $this->form_validation->set_rules('username', 'Потребителско име',
            'required');
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('header');
            $this->load->view('edit_user');
            $this->load->view('footer');
        } else {
            $name = $this->input->post('username');
            $user = $this->users_model->find_by_name($name);
            $data = array();
            if ($user === NULL) {
                $data['username'] = $this->input->post('username');
                $this->session->set_flashdata('msg', 'Потребителят "' . htmlspecialchars($name) . '" не съществува.');
            } else {
                $data['user'] = $user;
                $data['username'] = $user->username;
            }
            $this->load->view('header');
            $this->load->view('edit_user', $data);
            $this->load->view('footer');
        }
    }

    private function post_edit_update() {
        $this->verify_admin();
        $data = array(
            'first_name' => $this->input->post('first_name'),
            'last_name' => $this->input->post('last_name'),
            'username' => $this->input->post('username'),
            'is_admin' => intval($this->input->post('is_admin')),
            'occupation' => $this->input->post('occupation'),
            'city' => $this->input->post('city'),
            'employee' => $this->input->post('employee'),
            'modified_by' => $this->session->user->id
        );
        $id = intval($this->input->post('id'));
        if (!$this->users_model->update($id, $data)) {
            $this->session->set_flashdata('msg', 'Грешка! Потребителят не можа да бъде обновен.');
        } else {
            $this->session->set_flashdata('msg', 'Потребителят беше обновен успешно.');
        }

        $data['username'] = $this->input->post('username');
        $this->load->view('header');
        $this->load->view('edit_user', $data);
        $this->load->view('footer');
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
            $data = array(
                'first_name' => $this->input->post('first_name')
                , 'last_name' => $this->input->post('last_name')
                , 'is_admin' => intval($this->input->post('is_admin'))
                , 'username' => $this->input->post('username')
                , 'password' => sha1($this->input->post('password'))
                , 'occupation' => $this->input->post('occupation')
                , 'city' => $this->input->post('city')
                , 'employee' => $this->input->post('employee')
                , 'modified_by' => $this->session->user->id
            );
            if ($this->users_model->create($data, $this->session->user->id)) {
                $this->session->set_flashdata('msg', 'Потребителят беше създаден успешно.');
            } else {
                $this->session->set_flashdata('msg', 'cannot create user');
            }
            redirect('/users/');
        }
    }

    public function post_search() {
        if (!$this->session->user)
            show_404();
        $this->form_validation->set_rules('egn', 'EGN', 'required');
        $this->form_validation->set_rules('captcha_word', 'Captcha', 'required');
        $this->form_validation->set_rules('egn', 'EGN', 'callback_egn_check');
        if ($this->form_validation->run() === FALSE) {
            $this->index();
            return;
        }

        $this->captcha_model->delete_expired();
        $captcha_word = $this->input->post('captcha_word');
        $ip_addr = $this->input->ip_address();
        $captcha = $this->captcha_model->exists($captcha_word, $ip_addr);

        if (!$captcha) {
            $this->session->set_flashdata('msg', 'Невалидна captcha.');
            $this->index();
            return;
        }

        $egn = $this->input->post('egn');
        $user_id = $this->session->user->id;
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
        $sql_data = array(
            'egn' => $egn,
            'user_id' => $user_id,
            'ip_addr' => $ip_addr,
        );
        $this->egn_model->add($sql_data);
        $this->session->set_flashdata('msg', $msg);
        $this->session->set_flashdata('color', $color);
        $this->session->set_userdata('egn', $egn);
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

    private function create_captcha() {
        $vals = array(
            'img_path' => './captcha/',
            'img_url' => base_url() . 'captcha/',
            'img_width' => 150,
            'img_height' => 100,
            'expiration' => 7200
        );

        $cap = create_captcha($vals);
        $cap_data = array(
            'captcha_time'  => $cap['time'],
            'ip_address'    => $this->input->ip_address(),
            'word'          => $cap['word']
        );

        $query = $this->db->insert_string('captcha', $cap_data);
        $this->db->query($query);
        return $cap;
    }
}
