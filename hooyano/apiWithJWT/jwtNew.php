<?php
    // include this to send a new JWT to the client ONLY after authorizing the incoming JWT

    $iv_len = openssl_cipher_iv_length($cipher);
    $iv = openssl_random_pseudo_bytes($iv_len);
    $e_id = openssl_encrypt($user_id, $cipher, $key, $options = 0, $iv, $tag1);

    // I know this isn't a good key, but it'll do for now
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