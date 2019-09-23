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

    $username = $input['username'];
    $password = $input['password'];
    
    // apparently don't need to sanitize the vars when using prepare and bind_param
    $response["username"] = $username;

    // check that username and password exist
    if (isset($input['username']) && isset($input['password']))
    {
        // hashed_pass and salt needed to verify correct password
        // user_id needed for later when we want to look up contacts that belong to this user
        $select_query = "SELECT `user_id`, `full_name`, `hashed_pass`, `salt`, `created_date` FROM `$user_table` WHERE `username` = ?";
        if ($stmt = $con->prepare($select_query))
        {
            $stmt->bind_param("s", $username);
            if ($stmt->execute())
            {
                // create variables and pass data from table into them
                $stmt->bind_result($user_id, $full_name, $hashed_pass, $salt, $created_date);
                // check username, fails when fetch can't find matching username
                if ($stmt->fetch())
                {
                    // check password
                    if (password_verify(concatPasswordWithSalt($password, $salt), $hashed_pass))
                    {
                        // After successful login, we can use $_SESSION to keep user logged in during
                        // their visit and log them out when they want with the same $_SESSION
                        $response["user_id"] = $user_id;
                        $response["full_name"] = $full_name;
                        $response["created_date"] = $created_date;
                        $response["status"] = 0;
                        $response["message"] = "Login successful.";
                    }
                    else
                    {
                        $response["status"] = 1;
                        $response["message"] = "Invalid password.";
                    }
                }
                else
                {
                    $response["status"] = 1;
                    $response["message"] = "Invalid username.";
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
        $response["message"] = "Required field (username or password) is missing information.";
    }

    echo json_encode($response);
    mysqli_close($con);
?>