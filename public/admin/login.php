<?php
    session_start();

?>

<html lang="en">
    <head>
        <title>Kosmoss CMS | Sign in to Admin Portal</title>
    </head>
    <body>
        <form method="post" action="login.action.php">
            <label for="admin-username">Username</label>
            <span class="input-error">
                <?php if(!empty($_SESSION['admin-username-error'])) { echo $_SESSION['admin-username-error']; } ?>
            </span>
            <input type="text" maxlength="100" name="admin-username" id="admin-username">

            <label for="admin-password">Password</label>
            <span class="input-error">
                <?php if(!empty($_SESSION['admin-password-error'])) { echo $_SESSION['admin-password-error']; } ?>
            </span>
            <input type="password" maxlength="255" name="admin-password" id="admin-password">

            <input type="submit" value="Login">
        </form>
    </body>
</html>