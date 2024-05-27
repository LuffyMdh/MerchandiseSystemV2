<?php 
    session_start();
    include 'connect.php';
    $filter = $_GET['status'];

    if ($filter != 1 && $filter != 0 &&  $filter != -1) {
        $sqlCountRecord = $conn->prepare("SELECT COUNT(*) AS totalRecord
        FROM request
        WHERE request.user_id = ?;");
        $sqlCountRecord->bind_param("s", $_SESSION['loggedin']);
    } else {
        $sqlCountRecord = $conn->prepare("SELECT COUNT(*) AS totalRecord
        FROM request
        WHERE request.user_id = ?
        AND request.request_status = ?;");
        $sqlCountRecord->bind_param("ss", $_SESSION['loggedin'], $filter);
    }
    
    $sqlCountRecord->execute();
    $countResult = $sqlCountRecord->get_result();
    $totalRecord = $countResult->fetch_assoc();

    echo ceil($totalRecord['totalRecord'] / 10);
?>