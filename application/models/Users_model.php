<?php

class Users_model extends CI_Model {
    public function __construct() {
    }

    public function all() {
        $q = $this->db->get('users');
        return $q->row_array();
    }

    public function login($username, $password) {
        $q = $this->db->get_where('users', array('username' => $username, 'password' => sha1($password)));
        if ($q->num_rows() > 0)
            return $q->row();
        return NULL;
    }

    public function find_by_name($username) {
        $q = $this->db->get_where('users', array('username' => $username));
        if ($q->num_rows() > 0)
            return $q->row();
        return NULL;
    }
}
