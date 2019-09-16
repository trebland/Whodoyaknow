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

    // apparently don't need to sanitize the vars when using prepare and bind_param
    /*
    $contact_id = intval($contact_id);
    $user_id = intval($user_id);
    */

    $response["contact_id"] = $contact_id;
    $response["user_id"] = $user_id;

    // contact_id and user_id needed to lookup matching contacts from the database
    if (isset($input['contact_id']) && isset($input['user_id']))
    {
        $select_query = "SELECT `name`, `phone`, `address`, `website`, `email` FROM `$contact_table` WHERE `contact_id` = ? AND `user_id` = ?";
        if ($stmt = $con->prepare($select_query))
        {
            $stmt->bind_param("ii", $contact_id, $user_id);
            if ($stmt->execute())
            {
                $stmt->bind_result($name, $phone, $address, $website, $email);
                if ($stmt->fetch())
                {
                    $response["name"] = $name;
                    $response["phone"] = $phone;
                    $response["address"] = $address;
                    $response["website"] = $website;
                    $response["email"] = $email;
                    $response["status"] = 0;
                    $response["message"] = "Successfully found the contact.";
                }
                else
                {
                    $response["status"] = 1;
                    $response["message"] = "Could not find contact.";
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
        $response["message"] = "Required field (contact_id or user_id) is missing information.";
    }

    echo json_encode($response);
    mysqli_close($con);
?>
