<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php echo validation_errors(); ?>


<?php echo form_open('users/post_edit'); ?>
<?php 
    if ($this->session->flashdata('msg')) {
        echo '<p style="color:red">', $this->session->flashdata('msg'), '</p>';
    }
?>

Потребителско име:
<input type="text" name="username" value="<?php if (isset($username)) { echo $username; } ?>"/>
<input type="submit" name="submit" value="Търси" />
<input type="hidden" name="form" value="search" />
</form>
<br />
<hr />
<br />

<?php if (isset($user)): ?>
<?php echo form_open('users/post_edit'); ?>
<table>
    <tr>
        <td>Id</td>
        <td><input type="text" name="id" value="<?= $user->id; ?>" readonly /></td>
    </tr>
    <tr>
        <td>Име</td>
        <td><input type="text" name="first_name" value="<?= $user->first_name; ?>" /></td>
    </tr>
    <tr>
        <td>Фамилия</td>
        <td><input type="text" name="last_name" value="<?= $user->last_name; ?>" /></td>
    </tr>
    <tr>
        <td>Потребителско име</td>
        <td><input type="text" name="username" value="<?= $user->username; ?>" /></td>
    </tr>
    <tr>
        <td>Администратор?(1/0)</td>
        <td><input type="text" name="is_admin" value="<?= $user->is_admin; ?>" /></td>
    </tr>
    <tr>
        <td>Длъжност</td>
        <td><input type="text" name="occupation" value="<?= $user->occupation; ?>" /></td>
    </tr>
    <tr>
        <td>Град</td>
        <td><input type="text" name="city" value="<?= $user->city; ?>" /></td>
    </tr>
    <tr>
        <td>Работодател</td>
        <td><input type="text" name="employee" value="<?= $user->employee; ?>" /></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" name="submit" value="Промени" /></td>
    </tr>
</table>
<input type="hidden" name="form" value="edit" />
</form>
<?php endif; ?>
