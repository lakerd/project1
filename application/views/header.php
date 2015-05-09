 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <html>
            <head></head>
            <body>
<?php
    $user = $this->session->user;
    if ($user) { 
        if ($user->is_admin) {
            echo anchor('users', 'Начало');
            echo '<br />';
            echo anchor('users/create', 'Създаване на нов потребител');
            echo '<br />';
            echo anchor('users/edit', 'Променя на потребител');
            echo '<br />';
        }
        echo anchor('users/logout', 'Изход');
    }
?>
<br />
