<?php
    include 'connect.php';

    if (isset($_POST['data'])) {
        $data = $_POST['data'];
        $requestAssignmentArray = array();
        $requestIdArray = array();
        $notiMsgArray = array();
        $stmtVarTotal = getTotalQMark($data);
        $bindVarTotal = getTotalSMark($data);
        $bindNotiVar = '';
        $bindRejectVar = '';
        $admin = $_SESSION['loggedin'];

        // print_r($stmtVarTotal);
        // print_r($bindVarTotal);
        // // print_r(...$data);
        // var_dump(...$data);

        try {
            $conn->begin_transaction();
            $deleteProductStmt = $conn->prepare("UPDATE product 
                                                    SET product_status = 0
                                                    WHERE product_id
                                                    IN ($stmtVarTotal);");
            $deleteProductStmt->bind_param($bindVarTotal, ...$data);

            $getRequestStmt = $conn->prepare("SELECT DISTINCT rd.request_id, rq.user_id
                                                FROM requestdetail AS rd
                                                INNER JOIN request AS rq
                                                ON rd.request_id = rq.request_id
                                                WHERE rd.product_id
                                                IN ($stmtVarTotal)
                                                ORDER BY rq.user_id;");
            $getRequestStmt->bind_param($bindVarTotal, ...$data);
            $getRequestStmt->execute();
            $getRequestResult = $getRequestStmt->get_result();

            $rejectRequestSQL = "INSERT INTO requestassignment(request_id, admin_in_charge, comment, date) VALUES ";
            $notiMsgSQL = "INSERT INTO notification(noti_id, noti_msg, noti_isRead, noti_date, user_id) VALUES ";


            if (mysqli_num_rows($getRequestResult) > 0) {
                $preUserId = ''; // Track previous user id to avoid duplication
                while($requestId = $getRequestResult->fetch_assoc()) {
                    array_push($requestAssignmentArray, $requestId['request_id'], $_SESSION['loggedin'], 'Request rejected, merchandise unavailable.', $dateTime);
                    array_push($requestIdArray, $requestId['request_id']);
                    if (strcmp($requestId['user_id'], $preUserId) !== 0) {
                        $notiId = createUniqueId("NT");
                        $notiMsg = 'Your request ID #' . $requestId['request_id'] . ' has been rejected!';
                        array_push($notiMsgArray, $notiId, $notiMsg, '0', $dateTime, $requestId['user_id']);
                        $notiMsgSQL .= "(?,?,?,?,?),";
                        $bindNotiVar .= 'sssss';
                        $preUserId = $requestId['user_id'];
                    }

                    $rejectRequestSQL .= "(?,?,?,?),";
                    $bindRejectVar .= 'ssss';
                }

                $rejectRequestSQL = substr($rejectRequestSQL, 0, -1);
                $rejectRequestSQL .= ';';
                $notiMsgSQL = substr($notiMsgSQL, 0, -1);
                $notiMsgSQL .= ';';

                $stmtRequestVarTotal = getTotalQMark($requestIdArray);
                $sqlRequestVarTotal = getTotalSMark($requestIdArray);
                
                $updateRequestStatusStmt = $conn->prepare("UPDATE request 
                                                        SET request_status = -1
                                                        WHERE request_id
                                                        IN ($stmtRequestVarTotal) AND request_status = 0;");
                $updateRequestStatusStmt->bind_param($sqlRequestVarTotal, ...$requestIdArray);

                $rejectRequestStmt = $conn->prepare($rejectRequestSQL);
                $rejectRequestStmt->bind_param($bindRejectVar, ...$requestAssignmentArray);
                $rejectRequestStmt->execute();

                
                $notiMsgStmt = $conn->prepare($notiMsgSQL);
                $notiMsgStmt->bind_param($bindNotiVar, ...$notiMsgArray);
                $notiMsgStmt->execute();
                $updateRequestStatusStmt->execute();
            }


            $deleteProductStmt->execute();
    
            
  
            $conn->commit();

            $productList = json_encode($data);
            $requestList = json_encode($requestIdArray);

            writeToLog("\n" . funcGetDate('MY') . ": List of product removed - $productList. List of request rejected - $requestList. This transaction is done by [$admin]\n");
        } catch (Throwable $e) {
            echo $e;
            echo mysqli_error($conn);
            $conn->rollback();
        }
    }
?>