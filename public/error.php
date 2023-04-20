<?php
    $errorCodes = array(
        403 => 'It looks like you are not authorized to view this page.',
        404 => 'The requested file could not be found.',
        500 => 'Internal server error.'
    );

    // Setting the internal error code status.
    $errorCode = 500;
    if(array_key_exists($_SERVER['REDIRECT_STATUS'], $errorCodes))
    {
        $errorCode = $_SERVER['REDIRECT_STATUS'];
    }

    $errorMessage = $errorCodes[$errorCode];
?>

<html lang="en">
    <head>
        <title>Oh no, an error!</title>
    </head>
    <body>
        <h1><?php echo $errorCode; ?></h1>
        <span><?php echo $errorMessage; ?></span>
    </body>
</html>
