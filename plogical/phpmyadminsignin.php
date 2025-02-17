<?php


define("PMA_SIGNON_INDEX", 1);

try {
    define('PMA_SIGNON_SESSIONNAME', 'SignonSession');
    define('PMA_DISABLE_SSL_PEER_VALIDATION', TRUE);

    if (isset($_POST['token'])) {

        ### Get credentials using the token

        $token = $_POST['token'];
        $username = $_POST['username'];

        //$url = "/dataBases/fetchDetailsPHPMYAdmin?token=" . $token . '&username=' . $username;
        $url = "/dataBases/fetchDetailsPHPMYAdmin";

        //         header('Location: ' . $url);

        // Redirect with POST data

        echo '<form id="redirectForm" action="' . $url . '" method="post">';
        echo '<input type="hidden"  value="' . $token . '" name="token">';
        echo '<input type="hidden"  value="' . $username . '" name="username">';
        echo '</form>';
        echo '<script>document.getElementById("redirectForm").submit();</script>';

    } else if (isset($_POST['logout'])) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 86400, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        session_destroy();
        header('Location: /base/');
        return;
    } else if (isset($_POST['password'])) {

        session_name(PMA_SIGNON_SESSIONNAME);
        @session_start();

        $username = $_POST['username'];
        $password = $_POST['password'];

        $_SESSION['PMA_single_signon_user'] = $username;
        $_SESSION['PMA_single_signon_password'] = $password;
        $_SESSION['PMA_single_signon_host'] = 'localhost';

        @session_write_close();

        header('Location: /phpmyadmin/index.php?server=' . PMA_SIGNON_INDEX);
    }
} catch (Exception $e) {
    echo 'Caught exception: ', $e->getMessage(), "\n";
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 86400, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
    session_destroy();
    header('Location: /dataBases/phpMyAdmin');
    return;
}
