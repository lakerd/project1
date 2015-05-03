<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<?php echo validation_errors(); ?>
<?php echo form_open(); ?>

<?php 
    if ($this->session->flashdata('msg')) {
        echo '<p style="color:red">', $this->session->flashdata('msg'), '</p>';
    }
?>


<table>
    <tr><td>Потребителко име</td><td><input type="text" name="username" /></td></tr>
    <tr><td>Парола</td><td><input type="password" name="password" /></td></tr>
    <tr><td colspan="2"><input type="submit" name="login" value="Вход" /></td></tr>
</table>
</form>
