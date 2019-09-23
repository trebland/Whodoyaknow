<?php
    // written by: Phillip Tat from GROUP 10(Trevor Bland, Zach Arehart, Rob Lee, and Phillip Tat)
    // date written: 9/12/19
    // course: COP 4331(Rick Leinecker)
    // purpose: Small Project: Contact Manager(php api in LAMP stack)

    include 'dbConnection.php';
    include 'functions.php';
    $response = array();
    $contact_table = "contacts";

    $input_JSON = file_get_contents('php://input');
    $input = json_decode($input_JSON, TRUE);

    // need user_id to identify which user the contact will belong to
    // other fields are contact fields we'll likely want, but we can add or subtract from this list as needed
    $user_id = $input['user_id'];
    $name = $input['name'];
    $phone = $input['phone'];
    $address = $input['address'];
    $website = $input['website'];
    $email = $input['email'];

    // apparently don't need to sanitize the vars when using prepare and bind_param
    $response["user_id"] = $user_id;
    $response["name"] = $name;
    $response["phone"] = $phone;
    $response["address"] = $address;
    $response["website"] = $website;
    $response["email"] = $email;

    // bare minimum to create a contact is the user_id and name
    if (isset($input['user_id']) && isset($input['name']))
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
                                $response["contact_id"] = $contact_id;
                                $response["status"] = 0;
                                $response["message"] = "Contact created successfully.";
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
        $response["message"] = "Required field (user_id or name) is missing information.";
    }

    echo json_encode($response);
    mysqli_close($con);
?>