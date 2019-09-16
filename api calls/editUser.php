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
    $username = $input['username'];
    $password = $input['password'];
    $new_pass = $input['new_pass'];
    $full_name = $input['full_name'];
    $new_name = $input['new_name'];

    // apparently don't need to sanitize the vars when using prepare and bind_param
    /*
    $user_id = intval($user_id);
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);
    $new_pass = mysqli_real_escape_string($con, $new_pass);
    $full_name = mysqli_real_escape_string($con, $full_name);
    $new_name = mysqli_real_escape_string($con, $new_name);
    */

    $response["user_id"] = $user_id;
    $response["username"] = $username;
    $response["password"] = $new_pass;
    $response["full_name"] = $new_name;

    if (isset($input['user_id']) && isset($input['username']))
    {
        // check if both full_name and password need to be updated
        if ($full_name !== $new_name && $password !== $new_pass)
        {
            $salt = getSalt();
            $hashed_pass = password_hash(concatPasswordWithSalt($new_pass, $salt), PASSWORD_DEFAULT);

            $update_query = "UPDATE $user_table SET `full_name` = ?, `hashed_pass` = ?, `salt` = ? WHERE `user_id` = ? AND `username` = ?";
            if ($stmt = $con->prepare($update_query))
            {
                $stmt->bind_param("sssis", $new_name, $hashed_pass, $salt, $user_id, $username);
                if ($stmt->execute())
                {
                    if ($stmt->affected_rows > 0)
                    {
                        $response["status"] = 0;
                        $response["message"] = "User's full name and password has been updated.";
                    }
                    elseif ($stmt->affected_rows === 0)
                    {
                        $response["status"] = 0;
                        $response["message"] = "No changes made to the user's full name or password.";
                    }
                    else
                    {
                        $response["status"] = 5;
                        $response["message"] = "Failed to update the user's full name and password.";
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
        // check if only full_name needs to be updated
        elseif ($full_name !== $new_name)
        {
            $update_query = "UPDATE $user_table SET `full_name` = ? WHERE `user_id` = ? AND `username` = ?";
            if ($stmt = $con->prepare($update_query))
            {
                $stmt->bind_param("sis", $new_name, $user_id, $username);
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
        // check if only password needs to be updated
        elseif ($password !== $new_pass)
        {
            $salt = getSalt();
            $hashed_pass = password_hash(concatPasswordWithSalt($new_pass, $salt), PASSWORD_DEFAULT);
            $hashed_pass = mysqli_real_escape_string($con, $hashed_pass);

            $update_query = "UPDATE `$user_table` SET `hashed_pass` = ?, `salt` = ? WHERE `user_id` = ? AND `username` = ?";
            if ($stmt = $con->prepare($update_query))
            {
                $stmt->bind_param("ssis", $hashed_pass, $salt, $user_id, $username);
                if ($stmt->execute())
                {
                    if ($stmt->affected_rows > 0)
                    {
                        $response["status"] = 0;
                        $response["message"] = "User's password has been updated.";
                    }
                    elseif ($stmt->affected_rows === 0)
                    {
                        $response["status"] = 0;
                        $response["message"] = "No changes made to the user's password.";
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
            $response["status"] = 0;
            $response["message"] = "No change made.";
        }
    }
    else
    {
        $response["status"] = 2;
        $response["message"] = "Required field (user_id or username) is missing information.";
    }

    echo json_encode($response);
    mysqli_close($con);
?>
