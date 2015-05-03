 <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
        <html>
            <head></head>
            <body>
<?php
    $user = $this->session->user;
    if ($user) { 
        echo '<ul>';
        if ($user->is_admin) {
            echo '
                <li><a href="/users">Начало</a></li>
                <li><a href="/users/create">Създаване на нов потребител</a></li>
                <li><a href="/users/edit">Променя на потребител</a></li>
            ';
        }
        echo '<li><a href="/users/logout">Изход</a></li>';
        echo '</ul>';
    }
?>
