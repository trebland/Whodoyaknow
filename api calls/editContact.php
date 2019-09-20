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
    $name = $input['name'];
    $phone = $input['phone'];
    $address = $input['address'];
    $website = $input['website'];
    $email = $input['email'];

    // apparently don't need to sanitize the vars when using prepare and bind_param
    $response["contact_id"] = $contact_id;
    $response["name"] = $name;
    $response["phone"] = $phone;
    $response["address"] = $address;
    $response["website"] = $website;
    $response["email"] = $email;

    // check that contact_id and name exist
    if (isset($input['contact_id']) && isset($input['name']))
    {
        $update_query = "UPDATE `$contact_table` SET `name` = ?, `phone` = ?, `address` = ?,
                         `website` = ?, `email` = ? WHERE `contact_id` = ?";
        if ($stmt = $con->prepare($update_query))
        {
            $stmt->bind_param("sssssi", $name, $phone, $address, $website, $email, $contact_id);
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
        $response["message"] = "Required field (contact_id or name) is missing information.";
    }

    echo json_encode($response);
    mysqli_close($con);
?>