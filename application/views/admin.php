<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php 
    if ($this->session->flashdata('msg')) {
        echo '<p>', $this->session->flashdata('msg'), '</p>';
    }
?>

<p>Здравейте, <?= $user->username; ?></p>

<a href="##" onClick="history.go(-1); return false;">Назад</a>
