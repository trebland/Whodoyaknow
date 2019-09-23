<?php
    // written by: Phillip Tat from GROUP 10(Trevor Bland, Zach Arehart, Rob Lee, and Phillip Tat)
    // date written: 9/12/19
    // course: COP 4331(Rick Leinecker)
    // purpose: Small Project: Contact Manager(php api in LAMP stack)

    require_once('vendor/autoload.php');
    use \Firebase\JWT\JWT;
    include 'dbConnection.php';
    include 'functions.php';
    include 'jwtAuth.php';

    // authorize the JWT
    if ($jwt)
    {
        try
        {
            $decoded = JWT::decode($jwt, $jwtKey, array('HS256'));
            $decoded = json_decode(json_encode($decoded), True);

            $e_id = $decoded['data']['e_id'];
            $iv = base64_decode($decoded['data']['iv']);
            $tag1 = base64_decode($decoded['data']['tag1']);
            
            $user_id = openssl_decrypt($e_id, $cipher, $key, $options = 0, $iv, $tag1);

            $response["status"] = 0;
            $response["message"] = "Access granted.";
        }
        catch (\Exception $e)
        {
            $response["status"] = 6;
            $response["message"] = "Access denied. Error: " . $e->getMessage();
            http_response_code(401);
        }
    }
    else
    {
        $response["status"] = 7;
        $response["message"] = "Missing JWT, access denied.";
        http_response_code(400);
    }

    // these variables need to remain before if condition
    // $user_id = $input['user_id'];
    $password = $input['password'];
    $new_pass = $input['new_pass'];

    // apparently don't need to sanitize the vars when using prepare and bind_param
    // $response["user_id"] = $user_id;

    if ($decoded['exp'] > time())
    {
        // check that user_id exists
        if (isset($user_id))
        {
            // check if only password needs to be updated
            if ($password !== $new_pass)
            {
                $salt = getSalt();
                $hashed_pass = password_hash(concatPasswordWithSalt($new_pass, $salt), PASSWORD_DEFAULT);
                $hashed_pass = mysqli_real_escape_string($con, $hashed_pass);

                $update_query = "UPDATE `$user_table` SET `hashed_pass` = ?, `salt` = ? WHERE `user_id` = ?";
                if ($stmt = $con->prepare($update_query))
                {
                    $stmt->bind_param("ssi", $hashed_pass, $salt, $user_id);
                    if ($stmt->execute())
                    {
                        if ($stmt->affected_rows > 0)
                        {
                            include 'jwtNew.php';
                            $jwt = JWT::encode($token, $jwtKey);
                            $response["status"] = 0;
                            $response["message"] = "User's password has been updated.";
                            $response["jwt"] = $jwt;
                            $response["expireAt"] = $expire;
                        }
                        elseif ($stmt->affected_rows === 0)
                        {
                            include 'jwtNew.php';
                            $jwt = JWT::encode($token, $jwtKey);
                            $response["status"] = 0;
                            $response["message"] = "No changes made to the user's password.";
                            $response["jwt"] = $jwt;
                            $response["expireAt"] = $expire;
                        }
                        else
                        {
                            $response["status"] = 5;
                            $response["message"] = "Failed to update the user's password.";
                        }
                    }
                    else
                    {
                        $response["status"] = 4;
                        $response["message"] = "Failed to execute query.";
                    }

                    $stmt->close();
                }
                else
                {
                    $response["status"] = 3;
                    $response["message"] = "Failed to prepare query.";
                }
            }
            else
            {
                include 'jwtNew.php';
                $jwt = JWT::encode($token, $jwtKey);
                $response["status"] = 0;
                $response["message"] = "No change made.";
                $response["jwt"] = $jwt;
                $response["expireAt"] = $expire;
            }
        }
        else
        {
            $response["status"] = 2;
            $response["message"] = "Required field (user_id) is missing information or JWT has been tampered with.";
        }
    }
    else
    {
        $response["status"] = 6;
        $response["message"] = "Access denied. JWT has expired.";
    }

    echo json_encode($response);
    mysqli_close($con);
?>