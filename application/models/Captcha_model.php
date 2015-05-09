<?php

class Captcha_model extends CI_Model {
    public function delete_expired() {
        $expiration = time() - 7200; // Two hour limit
        $this->db->where('captcha_time < ', $expiration)
            ->delete('captcha');
    }

    public function exists($word, $ip_addr) {
        $sql = 'SELECT captcha_id
            FROM captcha 
            WHERE word = ? AND ip_address = ? AND captcha_time > ?';
        $binds = array($word, $ip_addr, time() - 7200);
        $query = $this->db->query($sql, $binds);
        return $query->row();
    }
}
