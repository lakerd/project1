<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<br />

<center>
    <?php echo $pagination; ?>
</center>

<table align="center" border="1">
    <tr>
        <td align="center">ЕГН</td>
        <td align="center">Търсено на</td>
        <td align="center">Потребител</td>
        <td align="center">IP адрес</td>
    </tr>
<?php foreach ($logs as $log): ?>
    <tr>
        <td align="center"><?php echo $log->egn; ?></td>
        <td align="center"><?php echo $log->created_on; ?></td>
        <td align="center"><?php echo $log->username;; ?></td>
        <td align="center"><?php echo $log->ip_addr; ?></td>
    </tr>
<?php endforeach; ?>
</table>

<center>
    <?php echo $pagination; ?>
</center>
