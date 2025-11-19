<?php
include $_SERVER['DOCUMENT_ROOT'] . "/includes/config.php";


$apple_state = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyz"), 0, 30);
$_SESSION['apple_state'] = $apple_state;

header("Location:https://appleid.apple.com/auth/authorize?client_id=com.nebula3gamefi&redirect_uri=https://" . $HOST . ".nebula3gamefi.com/extern/sso/login_apple_web.php&response_type=code id_token&state=" . $apple_state . "&scope=email&response_mode=form_post");
?>