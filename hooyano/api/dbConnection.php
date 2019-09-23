<?php
    // written by: Phillip Tat from GROUP 10(Trevor Bland, Zach Arehart, Rob Lee, and Phillip Tat)
    // date written: 9/12/19
    // course: COP 4331(Rick Leinecker)
    // purpose: Small Project: Contact Manager(php api in LAMP stack)
    // JSON field named "status": 0 = no error, 1 = data conflict,
    // 2 = missing params, 3 = prepare failed, 4 = execute failed, 5 = query failed

    // how to connect to AWS RDS
    // $con = mysqli_connect($_SERVER['RDS_HOSTNAME'], $_SERVER['RDS_USERNAME'], $_SERVER['RDS_PASSWORD'], $_SERVER['RDS_DB_NAME'], $_SERVER['RDS_PORT']);
    $db_server = "ls-1d451f850bc37797edbce9f55e6f8d41cccf5cfe.chdomz9rccpe.us-east-1.rds.amazonaws.com";
    $db_user = "dbmasteruser";
    $db_pass = "yJ`~He<J~XYytOluZx`t0-VxJ<5qt3,g";
    $db_database = "hooyano";
    $db_port = "3306";

    // create connection
    $con = mysqli_connect($db_server, $db_user, $db_pass, $db_database, $db_port);
    // check connection
    if(!$con)
    {
        // die == exit
        die("Connection failed! " . $con->connect_error);
    }
?>