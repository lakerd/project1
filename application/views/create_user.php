<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php echo validation_errors(); ?>
<?php echo form_open('users/post_create'); ?>

<table>
    <tr>
        <td>First name</td>
        <td><input type="text" name="first_name" value="<?= set_value('first_name'); ?>" /></td>
    </tr>
    <tr>
        <td>Last name</td>
        <td><input type="text" name="last_name" value="<?= set_value('last_name'); ?>" /></td>
    </tr>
    <tr>
        <td>Username</td>
        <td><input type="text" name="username" value="<?= set_value('username'); ?>" /></td>
    </tr>
    <tr>
        <td>Password</td>
        <td><input type="password" name="password" value="<?= set_value('password'); ?>" /></td>
    </tr>
    <tr>
        <td>Is admin? (1/0)</td>
        <td><input type="text" name="is_admin" value="<?= set_value('is_admin'); ?>" /></td>
    </tr>
    <tr>
        <td>Occupation</td>
        <td><input type="text" name="occupation" value="<?= set_value('occupation'); ?>" /></td>
    </tr>
    <tr>
        <td>City</td>
        <td><input type="text" name="city" value="<?= set_value('city'); ?>" /></td>
    </tr>
    <tr>
        <td>Employee</td>
        <td><input type="text" name="employee" value="<?= set_value('employee'); ?>" /></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" name="submit" /></td>
    </tr>
</table>
</form>
