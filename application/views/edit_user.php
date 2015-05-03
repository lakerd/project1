<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<?php echo validation_errors(); ?>
<?php echo form_open('users/post_edit'); ?>

<table>
    <tr>
        <td>Id</td>
        <td><input type="text" name="id" value="<?= $user->id; ?>" readonly /></td>
    </tr>
    <tr>
        <td>First name</td>
        <td><input type="text" name="first_name" value="<?= $user->first_name; ?>" /></td>
    </tr>
    <tr>
        <td>Last name</td>
        <td><input type="text" name="last_name" value="<?= $user->last_name; ?>" /></td>
    </tr>
    <tr>
        <td>Username</td>
        <td><input type="text" name="username" value="<?= $user->username; ?>" /></td>
    </tr>
    <tr>
        <td>Is admin?</td>
        <td><input type="text" name="is_admin" value="<?= $user->is_admin; ?>" /></td>
    </tr>
    <tr>
        <td>Occupation</td>
        <td><input type="text" name="occupation" value="<?= $user->occupation; ?>" /></td>
    </tr>
    <tr>
        <td>City</td>
        <td><input type="text" name="city" value="<?= $user->city; ?>" /></td>
    </tr>
    <tr>
        <td>Employee</td>
        <td><input type="text" name="employee" value="<?= $user->employee; ?>" /></td>
    </tr>
    <tr>
        <td colspan="2"><input type="submit" name="submit" /></td>
    </tr>
</table>
</form>
