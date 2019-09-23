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
    $name = $input['name'];
    $phone = $input['phone'];
    $address = $input['address'];
    $website = $input['website'];
    $email = $input['email'];

    if ($input['expireAt'] > time())
    {
        // check that contact_id and name exist
        if (isset($input['contact_id']) && isset($input['name']) && isset($user_id))
        {
            $response["rowEdited"] = 0;
            
            $update_query = "UPDATE `$contact_table` SET `name` = ? WHERE `contact_id` = ?";
            if ($stmt = $con->prepare($update_query))
            {
                $stmt->bind_param("si", $name, $contact_id);
                if ($stmt->execute())
                {
                    if ($stmt->affected_rows > 0)
                    {
                        $response["rowEdited"] += 1;
                    }
                    elseif ($stmt->affected_rows === 0)
                    {
                        // do nothing
                    }
                    else
                    {
                        $response["status"] = 5;
                        $response["message"] = "Failed to update the contact.";
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

            if (isset($input['phone']))
            {
                $update_query = "UPDATE `$contact_table` SET `phone` = ? WHERE `contact_id` = ?";
                if ($stmt = $con->prepare($update_query))
                {
                    $stmt->bind_param("si", $phone, $contact_id);
                    if ($stmt->execute())
                    {
                        if ($stmt->affected_rows > 0)
                        {
                            $response["rowEdited"] += 1;
                        }
                        elseif ($stmt->affected_rows === 0)
                        {
                            // do nothing
                        }
                        else
                        {
                            $response["status"] = 5;
                            $response["message"] = "Failed to update the contact.";
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
            if (isset($input['address']))
            {
                $update_query = "UPDATE `$contact_table` SET `address` = ? WHERE `contact_id` = ?";
                if ($stmt = $con->prepare($update_query))
                {
                    $stmt->bind_param("si", $address, $contact_id);
                    if ($stmt->execute())
                    {
                        if ($stmt->affected_rows > 0)
                        {
                            $response["rowEdited"] += 1;
                        }
                        elseif ($stmt->affected_rows === 0)
                        {
                            // do nothing
                        }
                        else
                        {
                            $response["status"] = 5;
                            $response["message"] = "Failed to update the contact.";
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
            if (isset($input['website']))
            {
                $update_query = "UPDATE `$contact_table` SET `website` = ? WHERE `contact_id` = ?";
                if ($stmt = $con->prepare($update_query))
                {
                    $stmt->bind_param("si", $website, $contact_id);
                    if ($stmt->execute())
                    {
                        if ($stmt->affected_rows > 0)
                        {
                            $response["rowEdited"] += 1;
                        }
                        elseif ($stmt->affected_rows === 0)
                        {
                            // do nothing
                        }
                        else
                        {
                            $response["status"] = 5;
                            $response["message"] = "Failed to update the contact.";
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
            if (isset($input['email']))
            {
                $update_query = "UPDATE `$contact_table` SET `email` = ? WHERE `contact_id` = ?";
                if ($stmt = $con->prepare($update_query))
                {
                    $stmt->bind_param("si", $email, $contact_id);
                    if ($stmt->execute())
                    {
                        if ($stmt->affected_rows > 0)
                        {
                            $response["rowEdited"] += 1;
                        }
                        elseif ($stmt->affected_rows === 0)
                        {
                            // do nothing
                        }
                        else
                        {
                            $response["status"] = 5;
                            $response["message"] = "Failed to update the contact.";
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
        }
        else
        {
            $response["status"] = 2;
            $response["message"] = "Required field (contact_id or name) is missing information or JWT has been tampered with.";
        }

        if (!isset($response["status"]))
        {
            include 'jwtNew.php';
            $jwt = JWT::encode($token, $jwtKey);
            // apparently don't need to sanitize the vars when using prepare and bind_param
            $response["contact_id"] = $contact_id;
            $response["name"] = $name;
            $response["phone"] = $phone;
            $response["address"] = $address;
            $response["website"] = $website;
            $response["email"] = $email;

            if ($response["rowEdited"] === 0)
            {
                $response["status"] = 0;
                $response["message"] = "No changes made.";
            }
            else
            {
                $response["status"] = 0;
                $response["message"] = "Contact updated successfully.";
            }

            $response["jwt"] = $jwt;
            $response["expireAt"] = $expire;
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