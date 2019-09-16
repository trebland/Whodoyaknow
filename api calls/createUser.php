<?php
    // written by: Phillip Tat from GROUP 10(Trevor Bland, Zach Arehart, Rob Lee, and Phillip Tat)
    // date written: 9/12/19
    // course: COP 4331(Rick Leinecker)
    // purpose: Small Project: Contact Manager(php api in LAMP stack)

    include 'dbConnection.php';
    include 'functions.php';
    $response = array();
    $user_table = "users";

    // get JSON file
    $input_JSON = file_get_contents('php://input');
    // converts JSON into array
    $input = json_decode($input_JSON, TRUE);

    // extract JSON data from the array
    $username = $input['username'];
    $password = $input['password'];
    $full_name = $input['full_name'];

    // apparently don't need to sanitize the vars when using prepare and bind_param
    /*
    // sanitize the strings to prevent SQL injection attacks
    $username = mysqli_real_escape_string($con, $username);
    $password = mysqli_real_escape_string($con, $password);
    $full_name = mysqli_real_escape_string($con, $full_name);
    */

    // response array will become our JSON output
    $response["username"] = $username;
    // do I need to pass the password?
    $response["password"] = $password;
    $response["full_name"] = $full_name;

    // check that these three fields aren't empty because the
    // JSON fields should have username, password, and full_name
    if (isset($input['username']) && isset($input['password']) && isset($input['full_name']))
    {
        // check if username is taken
        if (!userExists($username))
        {
            $insert_query = "INSERT INTO `$user_table` (`username`, `full_name`, `hashed_pass`, `salt`) VALUES (?, ?, ?, ?)";
            if ($stmt = $con->prepare($insert_query))
            {
                $salt = getSalt();
                $hashed_pass = password_hash(concatPasswordWithSalt($password, $salt), PASSWORD_DEFAULT);

                $stmt->bind_param("ssss", $username, $full_name, $hashed_pass, $salt);
                if ($stmt->execute())
                {
                    $response["status"] = 0;
                    $response["message"] = "User created successfully.";
                }
                else
                {
                    $response["status"] = 4;
                    $response["message"] = "Failed to execute query.";
                    // for reference: Can do this to list error if needed
                    // $response["message"] = "Failed to insert user data into the table." . $stmt->error;
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
            $response["status"] = 1;
            $response["message"] = "This username has already been taken. Please choose a different username.";
        }
    }
    else
    {
        $response["status"] = 2;
        $response["message"] = "Required field (username, password, or full name) is missing information.";
    }

    // pass response back as JSON
    echo json_encode($response);
    // close the connection
    mysqli_close($con);
?>
