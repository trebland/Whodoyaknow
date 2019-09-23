<?php
    // for top of loginUser.php, right after include functions.php

    $response = array();
    $token = array();
    $data = array();
    $user_table = "users";

    $input_JSON = file_get_contents('php://input');
    $input = json_decode($input_JSON, TRUE);

    $username = $input['username'];
    $password = $input['password'];

    // I know this isn't a good key but it'll do for now
    $key = "SecretkeyForuserID";
    $cipher = 'aes-128-gcm';
    $iv_len = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($iv_len);
?>