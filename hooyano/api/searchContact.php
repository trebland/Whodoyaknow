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

    $user_id = $input['user_id'];
    $search = $input['search'];
    
    // apparently don't need to sanitize the vars when using prepare and bind_param
    $response["user_id"] = $user_id;
    $response["search"] = $search;

    // both user_id and search are required to find contacts
    if (isset($input['user_id']) && isset($input['search']))
    {
        $search_query = "SELECT `contact_id`, `name`, `phone`, `address`, `website`, `email` FROM `$contact_table`
                         WHERE `user_id` = ? AND `name` LIKE ? ORDER BY `name`";
        if ($stmt = $con->prepare($search_query))
        {
            $search = "%" . $search . "%";
            $stmt->bind_param("is", $user_id, $search);
            if ($stmt->execute())
            {
                $result = $stmt->get_result();
                while ($row = $result->fetch_assoc())
                {
                    $response["search_results"][] = $row;
                }
                
                if ($response["search_results"] !== NULL)
                {
                    $response["status"] = 0;
                    $response["message"] = "Successfully got all matches for the search term in contacts.";
                }
                else
                {
                    $response["status"] = 0;
                    $response["message"] = "No matches for the search term exists in contacts.";
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
        $response["message"] = "Required field (user_id or search) is missing information.";
    }

    echo json_encode($response);
    mysqli_close($con);
?>