<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
Здравейте, <?php echo $user->username; ?>
<br />
<?php echo validation_errors(); ?>
<?php echo form_open('/users/post_search'); ?>

<?php 
    if ($this->session->flashdata('msg')) {
        $color = $this->session->flashdata('color');
        echo '<p style="color:', $color, '">', $this->session->flashdata('msg'), '</p>';
    }
?>

ЕГН <input type="text" name="egn" value="<?= set_value('egn'); ?>" />
<br />

<input type="text" name="captcha" value="" />
<input type="submit" name="submit" value="Търси" />
</form>

<br />
<br />
