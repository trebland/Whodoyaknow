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

    // need user_id to identify which user the contact will belong to
    // other fields are contact fields we'll likely want, but we can add or subtract from this list as needed
    $name = $input['name'];
    $phone = $input['phone'];
    $address = $input['address'];
    $website = $input['website'];
    $email = $input['email'];

    if ($input['expireAt'] > time())
    {
        // bare minimum to create a contact is the user_id and name
        if (isset($user_id) && isset($input['name']))
        {
            $insert_query = "INSERT INTO `$contact_table` (`user_id`, `name`, `phone`, `address`, `website`, `email`) VALUES (?, ?, ?, ?, ?, ?)";
            if ($stmt = $con->prepare($insert_query))
            {
                $stmt->bind_param("isssss", $user_id, $name, $phone, $address, $website, $email);
                if ($stmt->execute())
                {
                    if ($stmt->affected_rows > 0)
                    {
                        $select_query = "SELECT `contact_id` FROM `$contact_table` WHERE `user_id` = ? AND `name` = ?";
                        if ($stmt = $con->prepare($select_query))
                        {
                            $stmt->bind_param("is", $user_id, $name);
                            if ($stmt->execute())
                            {
                                $stmt->bind_result($contact_id);
                                if ($stmt->fetch())
                                {
                                    include 'jwtNew.php';
                                    $jwt = JWT::encode($token, $jwtKey);
                                    $response["contact_id"] = $contact_id;
                                    $response["name"] = $name;
                                    $response["phone"] = $phone;
                                    $response["address"] = $address;
                                    $response["website"] = $website;
                                    $response["email"] = $email;
                                    $response["status"] = 0;
                                    $response["message"] = "Contact created successfully.";
                                    $response["jwt"] = $jwt;
                                    $response["expireAt"] = $expire;
                                }
                                else
                                {
                                    $response["status"] = 5;
                                    $response["message"] = "Failed to create contact.";
                                }
                            }
                            else
                            {
                                $response["status"] = 4;
                                $response["message"] = "Failed to execute query.";
                            }
                        }
                        else
                        {
                            $response["status"] = 3;
                            $response["message"] = "Failed to prepare query.";
                        }
                    }
                    else
                    {
                        $response["status"] = 5;
                        $response["message"] = "Failed to create contact.";
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
            $response["message"] = "Required field (user_id or name) is missing information or JWT has been tampered with.";
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