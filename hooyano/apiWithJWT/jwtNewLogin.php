<?php
    // for login.php right after password_verify and before $response
    
    // encrypt user_id
    $e_id = openssl_encrypt($user_id, $cipher, $key, $options = 0, $iv, $tag1);
    // create JWT
    // I know this isn't a good key, but it'll do for now
    $jwtKey = 'secretJWTkey';
    $serverName = '127.0.0.1';
    $issuedAt = time();
    $notBefore = $issuedAt;
    // JWT expires after an hour
    $expire = $notBefore + 3600;
    $data["e_id"] = $e_id;
    $data["iv"] = base64_encode($iv);
    $data["tag1"] = base64_encode($tag1);

    // required: iss == serverName, iat == issuedAt, nbf == notBefore,
    // exp == expire, and data == payload
    $token["iss"] = $serverName;
    $token["iat"] = $issuedAt;
    $token["nbf"] = $notBefore;
    $token["exp"] = $expire;
    $token["data"] = $data;
?>