<?php
    error_reporting(E_ERROR); 
    include 'connect.php';

    if((isset($_POST['type'])) && (isset($_POST['requestId'])) && (isset($_POST['reason'])) && isset($_POST['pickupTime'])) {
        $requestEvaluate = $_POST['type'];
        $requestId = $_POST['requestId'];
        $admin = $_SESSION['loggedin'];
        $requesterId = $_POST['requesterId'];
        $reason = $_POST['reason'];
        $proceed = true;

        ($_POST['reason'] != '') ? $reason = $_POST['reason'] : $reason = 'N/A';
        ($_POST['pickupTime'] != '') ? $pickupDate = $_POST['pickupTime'] : $pickupDate = '';

        

        if ($statusCode == 0) {
            if ($requestEvaluate == 1) {
                    try {

                        $updateRequestStmt = $conn->prepare('UPDATE request rq, requestdetail rd 
                                                                SET rq.request_status = 1, rd.request_product_status = 1 
                                                                WHERE EXISTS (SELECT request_id FROM requestdetail WHERE request_id = ?) AND rq.request_id = ? AND rd.request_id = ?;');
                        $updateRequestStmt->bind_param('sss', $requestId, $requestId, $requestId);

                        $insertIntoAdminStmt = $conn->prepare('INSERT INTO requestassignment(request_id, admin_in_charge, comment, date, pick_up_date)
                                                                VALUES(?, ?, ?, ?, str_to_date(?, "%d/%m/%Y %k:%i"));');
                        $insertIntoAdminStmt->bind_param('sssss', $requestId, $admin, $reason, $dateTime, $pickupDate);

                        $getRequestProductStmt = $conn->prepare('SELECT rd.product_id, rd.request_quan, req.mer_loc_id
                                                                    FROM requestdetail rd 
                                                                    INNER JOIN request req ON rd.request_id = req.request_id 
                                                                    WHERE req.request_id = ?;');
                        $getRequestProductStmt->bind_param('s', $requestId);

                        $getRequesterDetailStmt = $conn->prepare("SELECT us.name, us.email
                                                                    FROM request req
                                                                    INNER JOIN  $smgEmpTable us
                                                                    ON req.user_id = us.email
                                                                    WHERE req.request_id = ?");
                        $getRequesterDetailStmt->bind_param('s', $requestId);
                        $getRequesterDetailStmt->execute();

                        $getRequesterDetailResult = $getRequesterDetailStmt->get_result();
                        

                        

                        $updateProductQuantityStmt = $conn->prepare('UPDATE productquantity 
                                                                        SET product_quan = IF(product_quan - ? >= 0, product_quan - ?, product_quan)
                                                                        WHERE product_id = ? AND mer_loc_id = ?;');
                        $updateProductQuantityStmt->bind_param('ssss', $requestQuantity, $requestQuantity, $requestProductId, $requestLocation);
         

                        $conn->begin_transaction();
                            $updateRequestStmt->execute();

                            if ($updateRequestStmt->affected_rows > 0) {
                                $insertIntoAdminStmt->execute();

                                $getRequestProductStmt->execute();
                                $getRequestProductResult = $getRequestProductStmt->get_result();

                                if ($getRequestProductResult->num_rows > 0) {
                                    while ($getRequestProduct = $getRequestProductResult->fetch_assoc()) {
                                        $requestQuantity = $getRequestProduct['request_quan'];
                                        $requestProductId = $getRequestProduct['product_id'];
                                        $requestLocation = $getRequestProduct['mer_loc_id'];

                                        $updateProductQuantityStmt->execute();

                                        if ($updateProductQuantityStmt->affected_rows == 0) {
                                            $conn->rollback();
                                            $returnArray = ['returnCode' => 400, 'message' => "One of the merchandise quantity is not enough!"];
                                            writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " This transaction is done by [$admin]\n");
                                            echo json_encode($returnArray); 
                                            return;
                                        }
                                    }
                                } else {
                                    $conn->rollback();
                                    $returnArray = ['returnCode' => 400, 'message' => "No merchandise is selected!"];
                                    writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " This transaction is done by [$admin]\n");
                                    echo json_encode($returnArray); 
                                    return;
                                }


                            } else {
                                $conn->rollback();
                                $returnArray = ['returnCode' => 400, 'message' => "No merchandise is selected!"];
                                writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " This transaction is done by [$admin]\n");
                                echo json_encode($returnArray); 
                                return;
                            }

                        if ($getRequesterDetailResult->num_rows > 0) {
                            $getRequesterDetail = $getRequesterDetailResult->fetch_assoc();
                        } else {
                            $conn->rollback();
                            $returnArray = ['returnCode' => 400, 'message' => "Requester detail not found!"];
                            writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " This transaction is done by [$admin]\n");
                            echo json_encode($returnArray); 
                            return;
                        }


                        $conn->commit();
                        
                        $requesterEmail = $getRequesterDetail['email'];
                        $requesterName = $getRequesterDetail['name'];

 

                        $recipients = [$requesterEmail => $requesterName];    
                        $cc = array('corporatecomms@smg.my');
                        $name = '';
                        $email = '';
                        $password = '';
            
                        $subject = "[$requestId] - Your request has been approved!";
                        $body = "  <p>Request ID: $requestId</p>
                                    <p>Status: <span style='color: green'>Accepted</span></p>
                                    <p>Date Created: $dateTime</p>
                                    <p>Admin In Charge: $admin</p>
                                    <p>Pick up time: $pickupDate</p>
                                    <p>Click this <a href='https://merchandise.smg.my/'>link</a> to proceed. Thank you!</p>                        
                                    <br>
                                    <p>Best Regards,</p>
                                    <p>Merchandise System</p>";
            
            
                        $sendTheMail = new Mail($name, $email, $password, $subject, $body, true, $recipients, $cc);
                        $sendTheMail->sendTheEmail();
                        
                        $returnArray = ['returnCode' => 200, 'message' => "[$requestId] - Request is accepted."];
                        writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " This transaction is done by [$admin]\n");
                        echo json_encode($returnArray);
                        return;

                        return;
                    } catch (Exception $e)  {   
                        $tempArray = ['returnCode' => 500, 'message' => 'Error found: ' . $e . '. MySQL Error: ' . mysqli_error($conn)];
                        writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " This transaction is done by [$admin]\n");
                        echo json_encode($tempArray);
                        $conn->rollback();
                    } catch (Throwable $e) {
                        $tempArray = ['returnCode' => 500, 'message' => 'Error found: ' . $e . '. MySQL Error: ' . mysqli_error($conn)];
                        writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " This transaction is done by [$admin]\n");
                        echo json_encode($tempArray);
                        $conn->rollback();
                    }

            } else if ($requestEvaluate == -1) {
                try {

                    $updateRequestStatusStmt = $conn->prepare('UPDATE request
                                                                    SET request_status = -1
                                                                    WHERE request_id = ?;');
                    $updateRequestStatusStmt->bind_param('s', $requestId);

                    $updateRequestDetailStmt = $conn->prepare('DELETE FROM requestdetail
                                                                WHERE request_id = ?;');    
                    $updateRequestDetailStmt->bind_param('s', $requestId);

                    $deleteRequestDetailStmt = $conn->prepare('DELETE FROM requestdetail WHERE request_id = ?');
                    $deleteRequestDetailStmt->bind_param('s', $requestId);

                    $insertAdminStmt = $conn->prepare('INSERT INTO requestassignment(request_id, admin_in_charge, comment, date, pick_up_date)
                                                        VALUES(?, ?, ?, ?, NULL);');
                    $insertAdminStmt->bind_param('ssss', $requestId, $admin, $reason, $dateTime);

                    $getRequesterDetailStmt = $conn->prepare("SELECT us.name, us.email
                                                                FROM request req
                                                                INNER JOIN  $smgEmpTable us
                                                                ON req.user_id = us.email
                                                                WHERE req.request_id = ?");
                    $getRequesterDetailStmt->bind_param('s', $requestId);
                    $getRequesterDetailStmt->execute();
                    $getRequesterDetailResult = $getRequesterDetailStmt->get_result();

                    $conn->begin_transaction();
                        $updateRequestStatusStmt->execute();
                        $updateRequestDetailStmt->execute();
                        $insertAdminStmt->execute();
                        $deleteRequestDetailStmt->execute();


                        if ($getRequesterDetailResult->num_rows > 0) {
                            $getRequesterDetail = $getRequesterDetailResult->fetch_assoc();
                        } else {
                            $conn->rollback();
                            $returnArray = ['returnCode' => 400, 'message' => "Requester detail not found!"];
                            writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " This transaction is done by [$admin]\n");
                            echo json_encode($returnArray); 
                            return;
                        }

                    $conn->commit();


                    $requesterEmail = $getRequesterDetail['email'];
                    $requesterName = $getRequesterDetail['name'];


                    $recipients = [ $requesterEmail => $requesterName];    
                    $cc = array('corporatecomms@smg.my');
                    $name = '';
                    $email = '';
                    $password = '';


        
                    $subject = "[$requestId] - Your request has been denied!";
                    $body = "  <p>Request ID: $requestId</p>
                                <p>Status: <span style='color: red'>Rejected</span></p>
                                <p>Date Created: $dateTime</p>
                                <p>Admin In Charge: $admin</p>
                                <p>Reason: $reason</p>
                                <p>Click this <a href='https://merchandise.smg.my/'>link</a> to proceed. Thank you!</p>                                    
                                <br>
                                <p>Best Regards,</p>
                                <p>Merchandise System</p>";
        
        
                    $sendTheMail = new Mail($name, $email, $password, $subject, $body, true, $recipients, $cc);
                    $sendTheMail->sendTheEmail();

                    $returnArray = ['returnCode' => 201, 'message' => "[$requestId] - Request rejected! "];
                    echo json_encode($returnArray); 
                    return;

                } catch (Throwable $e) {
                    $conn->rollback();
                    $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
                    writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " This transaction is done by [$admin]\n");
                    echo json_encode($returnArray);
                    return;
                }
            }


    }

    } else {
        $returnArray = ['returnCode' => 500, 'message' => "Variable is not set."];
        writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " This transaction is done by [$admin]\n");
        echo json_encode($returnArray);
        return;
    }



?>