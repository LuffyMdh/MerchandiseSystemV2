<?php
    include 'connect.php';

    $userID = $_SESSION['loggedin'];

    try {
        $userStmt = $conn->prepare("SELECT name, email, phonenumber, division
        FROM $smgEmpTable
        WHERE email = ?;");
        $userStmt->bind_param('s', $userID);
        $userStmt->execute();
        $userResult = $userStmt->get_result();

        $userDetail = $userResult->fetch_assoc();

        echo json_encode($userDetail);

    } catch (Exception $e) {
        echo 'Caught exception: ',  $e->getMessage(), "\n";
    }

  
?>