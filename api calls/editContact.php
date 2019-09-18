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

    $contact_id = $input['contact_id'];
    $user_id = $input['user_id'];
    $name = $input['name'];
    $phone = $input['phone'];
    $address = $input['address'];
    $website = $input['website'];
    $email = $input['email'];

    // apparently don't need to sanitize the vars when using prepare and bind_param
    /*
    $contact_id = intval($contact_id);
    $user_id = intval($user_id);
    $name = mysqli_real_escape_string($con, $name);
    $phone = mysqli_real_escape_string($con, $phone);
    $address = mysqli_real_escape_string($con, $address);
    $website = mysqli_real_escape_string($con, $website);
    $email = mysqli_real_escape_string($con, $email);
    */

    $response["contact_id"] = $contact_id;
    $response["user_id"] = $user_id;
    $response["name"] = $name;
    $response["phone"] = $phone;
    $response["address"] = $address;
    $response["website"] = $website;
    $response["email"] = $email;

    if (isset($input['contact_id']))
    {
        $update_query = "UPDATE `$contact_table` SET `name` = ?, `phone` = ?, `address` = ?,
                         `website` = ?, `email` = ? WHERE `contact_id` = ? AND `user_id` = ?";
        if ($stmt = $con->prepare($update_query))
        {
            $stmt->bind_param("sssssii", $name, $phone, $address, $website, $email, $contact_id, $user_id);
            if ($stmt->execute())
            {
                if ($stmt->affected_rows > 0)
                {
                    $response["status"] = 0;
                    $response["message"] = "Contact successfully updated.";
                }
                elseif ($stmt->affected_rows === 0)
                {
                    $response["status"] = 0;
                    $response["message"] = "No changes made or the contact does not exist.";
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
    else
    {
        $response["status"] = 2;
        $response["message"] = "Required field (contact_id) is missing information.";
    }

    echo json_encode($response);
    mysqli_close($con);
?>