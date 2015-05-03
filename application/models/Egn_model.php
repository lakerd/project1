<?php
class Egn_model extends CI_Model {
    public function searched_in_3mo($egn) {
        $q = $this->db->select('egn')
            ->where('egn', $egn)
            ->where('created_on >=', 'NOW() - INTERVAL 90 DAY', FALSE)
            ->where('created_on <=', 'NOW()', FALSE)
            ->get('egn_log');
        return $q->num_rows();
    }

    public function egn_exists($egn) {
        $q = $this->db->get_where('egn_log', array('egn' => $egn));
        return $q->num_rows() > 0;
    }

    public function add($data) {
        $this->db->set('created_on', 'NOW()', FALSE);
        $this->db->insert('egn_log', $data);
    }
}
