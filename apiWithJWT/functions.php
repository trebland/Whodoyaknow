<?php
    // written by: Phillip Tat from GROUP 10(Trevor Bland, Zach Arehart, Rob Lee, and Phillip Tat)
    // date written: 9/12/19
    // course: COP 4331(Rick Leinecker)
    // purpose: Small Project: Contact Manager(php api in LAMP stack)

    function userExists($username)
    {
        global $con;

        // @TODO: replace username and users with actual db names
        $select_query = "SELECT `username` FROM `users` WHERE `username` = ?";
        if ($stmt = $con->prepare($select_query))
        {
            // the s tells bind_param that $username is a string
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $stmt->store_result();
            $stmt->fetch();
            
            if ($stmt->num_rows == 1)
            {
                $stmt->close();
                return true;
            }

            $stmt->close();
        }

        return false;
    }

    // convert binary to hex while getting 32 pseudo random bytes
    function getSalt()
    {
        $random_salt_length = 32;
        return bin2hex(openssl_random_pseudo_bytes($random_salt_length));
    }

    function concatPasswordWithSalt($password, $salt)
    {
        $random_salt_length = 32;
        $mid = $random_salt_length / 2;
        return substr($salt, 0, $mid - 1).$password.substr($salt, $mid, $random_salt_length - 1);
    }
?>