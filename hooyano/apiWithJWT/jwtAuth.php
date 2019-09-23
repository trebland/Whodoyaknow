<?php
    // for top of any API that needs JWT authorization

    $response = array();
    $token = array();
    $data = array();
    $user_table = "users";
    $contact_table = "contacts";

    $input_JSON = file_get_contents('php://input');
    $input = json_decode($input_JSON, TRUE);

    $jwtKey = 'secretJWTkey';
    $key = "SecretkeyForuserID";
    $cipher = 'aes-128-gcm';
    $jwt = $input['jwt'];
?>