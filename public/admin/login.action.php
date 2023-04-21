<?php

use Kosmoss\FileUtilities\EnvironmentFileHandler;
use Kosmoss\Database\SQLConnectionHandler;

session_start();

$_SESSION['admin-username-error'] = '';
$_SESSION['admin-password-error'] = '';

if($_SERVER['REQUEST_METHOD'] !== 'POST')
{
    header('Location: ../login.php', true, 301);
    exit();
}

/* Validating the Admin Username input.
 */
if(empty($_POST['admin-username']))
{
    $_SESSION['admin-username-error'] = 'Username is required';
}
if(strlen($_POST['admin-username']) > 5)
{
    $_SESSION['admin-username-error'] = 'Username must be less than 100 characters';
}

/* Validating the admin password field.
 */
if(empty($_POST['admin-password']))
{
    $_SESSION['admin-password-error'] = 'Password is required';
}
if(strlen($_POST['admin-password']) > 5)
{
    $_SESSION['admin-password-error'] = 'Password must be less than 255 characters';
}

/* Returning to the login form if any errors are present.
 */
if(!empty($_SESSION['admin-username-error']) or !empty($_SESSION['admin-password-error']))
{
    header('Location: ../login.php', true, 301);
    exit();
}

/* Attempting connection to the SQL database.
 */
$environmentVariables = EnvironmentFileHandler::parseENVFile('../.env');
if(!SQLConnectionHandler::connect())
{
    header('Location: ../error.php', true, 500);
    exit();
}

/* Attempting user login.
 */
$username = $_POST['admin-username'];
$password = password_hash($_POST['admin-password'], PASSWORD_DEFAULT);

$statement = SQLConnectionHandler::$sqlConnection->prepare('SELECT COUNT(*) FROM ?.tb_users WHERE username=? AND password=?');
$statement->bind_param('sss', $environmentVariables['SQL_DATABASE'], $username, $password);
$statement->execute();
$statement->bind_result($count);
$statement->fetch();

/* Redirecting back to form on failed login, otherwise redirecting to the admin dashboard.
 */
if($count === 0)
{
    $_SESSION['admin-username-error'] = 'User does not exist or incorrect password.';
    $_SESSION['admin-password-error'] = '';

    header('Location: ../login.php', true, 301);
}
else
{
    // TODO: give a cookie token of some sort to keep track if the user is logged in.
    // TODO: maybe the user didn't go to the login page right away and were not authorized to access an admin page, if that's the case maybe store that page in a session variable and redirect to the original admin page instead of dashboard.

    header('Location: ../admin/dashboard.php', true, 301);
}

exit();