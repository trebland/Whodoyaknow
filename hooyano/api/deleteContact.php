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

    $contact_id = $input['contact_id'];

    if ($input['expireAt'] > time())
    {
        // check that we have contact_id so that we delete the correct contact
        if (isset($input['contact_id']) && isset($user_id))
        {
            $delete_query = "DELETE FROM `$contact_table` WHERE `contact_id` = ?";
            if ($stmt = $con->prepare($delete_query))
            {
                $stmt->bind_param("i", $contact_id);
                if ($stmt->execute())
                {
                    if ($stmt->affected_rows > 0)
                    {
                        include 'jwtNew.php';
                        $jwt = JWT::encode($token, $jwtKey);
                        $response["status"] = 0;
                        $response["message"] = "Successfully deleted the contact from the database.";
                        $response["jwt"] = $jwt;
                        $response["expireAt"] = $expire;
                    }
                    elseif ($stmt->affected_rows === 0)
                    {
                        include 'jwtNew.php';
                        $jwt = JWT::encode($token, $jwtKey);
                        $response["status"] = 0;
                        $response["message"] = "The contact does not exist.";
                        $response["jwt"] = $jwt;
                        $response["expireAt"] = $expire;
                    }
                    else
                    {
                        $response["status"] = 5;
                        $response["message"] = "Failed to delete the contact.";
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
            $response["status"] = 2;
            $response["message"] = "Required field (contact_id) is missing information or JWT has been tampered with.";
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