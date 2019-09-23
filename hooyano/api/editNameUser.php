<?php
    // written by: Phillip Tat from GROUP 10(Trevor Bland, Zach Arehart, Rob Lee, and Phillip Tat)
    // date written: 9/12/19
    // course: COP 4331(Rick Leinecker)
    // purpose: Small Project: Contact Manager(php api in LAMP stack)

    include 'dbConnection.php';
    include 'functions.php';
    $response = array();
    $user_table = "users";

    $input_JSON = file_get_contents('php://input');
    $input = json_decode($input_JSON, TRUE);

    // these variables need to remain before if condition
    $user_id = $input['user_id'];
    $new_name = $input['new_name'];

    // apparently don't need to sanitize the vars when using prepare and bind_param
    $response["user_id"] = $user_id;

    // check that user_id exists
    if (isset($input['user_id']))
    {
        // check if only full_name needs to be updated
        if (isset($new_name))
        {
            $update_query = "UPDATE $user_table SET `full_name` = ? WHERE `user_id` = ?";
            if ($stmt = $con->prepare($update_query))
            {
                $stmt->bind_param("si", $new_name, $user_id);
                if ($stmt->execute())
                {
                    if ($stmt->affected_rows > 0)
                    {
                        $response["status"] = 0;
                        $response["message"] = "User's full name has been updated.";
                    }
                    elseif ($stmt->affected_rows === 0)
                    {
                        $response["status"] = 0;
                        $response["message"] = "No changes made to the user's full name.";
                    }
                    else
                    {
                        $response["status"] = 5;
                        $response["message"] = "Failed to update the user's full name.";
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
            $response["status"] = 0;
            $response["message"] = "No change made.";
        }
    }
    else
    {
        $response["status"] = 2;
        $response["message"] = "Required field (user_id) is missing information.";
    }

    echo json_encode($response);
    mysqli_close($con);
?>