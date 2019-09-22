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

    if ($input['expireAt'] > time())
    {
        // user_id needed to lookup matching contacts from the database
        if (isset($user_id))
        {
            $select_query = "SELECT `contact_id`, `user_id`, `name`, `phone`, `address`, `website`, `email` FROM `$contact_table` WHERE `user_id` = ?";
            if ($stmt = $con->prepare($select_query))
            {
                $stmt->bind_param("i", $user_id);
                if ($stmt->execute())
                {
                    $result = $stmt->get_result();
                    while ($row = $result->fetch_assoc())
                    {
                        $response["contacts"][] = $row;
                    }
                    
                    include 'jwtNew.php';
                    $jwt = JWT::encode($token, $jwtKey);

                    // check whether there were contacts pulled or not
                    if ($response["contacts"] !== NULL)
                    {
                        $response["status"] = 0;
                        $response["message"] = "Successfully got all contacts.";
                    }
                    else
                    {
                        $response["status"] = 0;
                        $response["message"] = "No contacts exist for this user.";
                    }

                    $response["jwt"] = $jwt;
                    $response["expireAt"] = $expire;
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