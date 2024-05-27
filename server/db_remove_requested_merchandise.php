<?php
    include 'connect.php';
    $tempArray = array();
    $admin = $_SESSION['loggedin'];

    if (isset($_POST['rejectAmt']) && isset($_POST['productId']) && isset($_POST['requestId']) && isset($_POST['rejectMsg'])) {
        $productId = $_POST['productId'];
        $requestId = $_POST['requestId'];
        $rejectAmt = $_POST['rejectAmt'];
        $rejectMsg = $_POST['rejectMsg'];
        

        try {
            $validateResult = adminRequestValidate($conn, $requestId, $productId);

            if (mysqli_num_rows($validateResult) > 0) {
                $requestQuantity = $validateResult->fetch_assoc();
                $requestQty = $requestQuantity['request_quan'];
                $checkRequestTotalActiveProduct = false;

                if ($rejectAmt == $requestQty) {
                    $conn->begin_transaction();
                    $updateProductStatusStmt = $conn->prepare('UPDATE requestdetail 
                                                                SET request_product_status = -1
                                                                WHERE product_id = ? AND request_id = ?');
                    $updateProductStatusStmt->bind_param('ss', $productId , $requestId);
                    $updateProductStatusStmt->execute();

                    $rejectAmtComment = "Merchandise is rejected. Reason: $rejectMsg" ;
                    $rejectAmtCommentStmt = $conn->prepare('INSERT INTO requestdetailcomment(rd_comment, rd_comment_date, product_id, request_id, admin)
                                                                VALUES(?, ?, ?, ?, ?)');
                    $rejectAmtCommentStmt->bind_param('sssss', $rejectAmtComment, $dateTime, $productId, $requestId, $admin);
                    $rejectAmtCommentStmt->execute();
                    $conn->commit();

                    $checkRequestTotalActiveProduct = true;
                    writeToLog("\n" . funcGetDate('MY') . ": [$requestId] [$productId] - $rejectAmtComment. Rejected by [$admin]. Total left requested: $newRequestedAmt.\n");

                    $conn->commit();
                    $checkRequestTotalActiveProduct = true;
                } else if  (($rejectAmt > 0) && ($rejectAmt < $requestQty)) {
                    $conn->begin_transaction();
                    $uptQtyStmt = $conn->prepare('UPDATE requestdetail, request
                                                    SET requestdetail.request_quan = (requestdetail.request_quan - ?), 
                                                        request.modify_date = ?
                                                    WHERE request.request_id = requestdetail.request_id AND requestdetail.request_id = ? AND requestdetail.product_id = ?;');
                    $uptQtyStmt->bind_param('ssss', $rejectAmt, $dateTime, $requestId, $productId);
                    $uptQtyStmt->execute();

              
                    $newRequestedAmt = $requestQty - $rejectAmt;
                    $rejectAmtComment = "Rejected amount: $rejectAmt. Reason: $rejectMsg" ;
                    
                    $rejectAmtCommentStmt = $conn->prepare('INSERT INTO requestdetailcomment(rd_comment, rd_comment_date, product_id, request_id, admin)
                                                                VALUES(?, ?, ?, ?, ?)');
                    $rejectAmtCommentStmt->bind_param('sssss', $rejectAmtComment, $dateTime, $productId, $requestId, $admin);
                    $rejectAmtCommentStmt->execute();
                    $conn->commit();

                    $checkRequestTotalActiveProduct = true;
                    writeToLog("\n" . funcGetDate('MY') . ": [$requestId] [$productId] - $rejectAmtComment. Rejected by [$admin]. Total left requested: $newRequestedAmt.\n");
                } else {
                    echo 'more than';
                }

                if ($checkRequestTotalActiveProduct) {
                    $getTotalActiveProductStmt = $conn->prepare('SELECT COUNT(product_id) AS totalActive
                                                                    FROM requestdetail 
                                                                    WHERE request_id = ? AND request_product_status = 0;');
                    $getTotalActiveProductStmt->bind_param('s', $requestId);
                    $getTotalActiveProductStmt->execute();
                    $getTotalActiveProductResult = $getTotalActiveProductStmt->get_result();

                    if ($getTotalActiveProductResult->num_rows > 0) {
                        $getTotalActiveProduct = $getTotalActiveProductResult->fetch_assoc();
                        $totalActiveProduct = $getTotalActiveProduct['totalActive'];

                        if ($totalActiveProduct == 0) {
                            try {
                                
                                $updateRequestStmt = $conn->prepare('UPDATE request
                                                                        SET request_status = -1
                                                                        WHERE request_id = ?');
                                $updateRequestStmt->bind_param('s', $requestId);
                                $conn->begin_transaction();
                                $updateRequestStmt->execute();
                                $conn->commit();
                                writeToLog("\n" . funcGetDate('MY') . ": [$requestId] - Request is rejected due to all product(s) is rejected.\n");
                            } catch (Error $e) {
                                $conn->rollback();
                            } catch (Exception $e) {
                                $conn->rollback();
                            }

                        }
                    }
                }


            } else {
                
            }
        } catch (Error $e) {
            $conn->rollback();
            $tempArray = ['returnCode' => 500, 'message' =>"Error found: $e"];
            writeToLog("\n" . funcGetDate('MY') . ": [Error found] - $e. [SQL] - " . mysqli_error($conn) . "\n");
        } catch (Exception $e) {
            $conn->rollback();
            $tempArray = ['returnCode' => 500, 'message' =>"Error found: $e"];
            writeToLog("\n" . funcGetDate('MY') . ": [Error found] - $e. [SQL] - " . mysqli_error($conn) . "\n");
        }

    } else if (isset($_POST['removeItemId']) && isset($_POST['requestId']) && isset($_POST['rejectReason'])) { // Remove merchandise from request
        $productId = $_POST['removeItemId'];
        $requestId = $_POST['requestId'];
        $reasonReject = $_POST['rejectReason'];
        
        try {
            $validateResult = adminRequestValidate($conn, $requestId, $productId);

            if (mysqli_num_rows($validateResult) > 0) {
                try {
                    $conn->begin_transaction();
                    $deleteMerchandiseStmt = $conn->prepare('UPDATE requestdetail
                                                                SET request_product_status = -1
                                                                WHERE request_id = ? AND product_id = ?;');
                    $deleteMerchandiseStmt->bind_param('ss', $requestId, $productId);
                    $deleteMerchandiseStmt->execute();

                    $rejectAmtComment = "Merchandise rejected. Reason: $reasonReject";

                    $rejectAmtCommentStmt = $conn->prepare('INSERT INTO requestdetailcomment(rd_comment, rd_comment_date, product_id, request_id, admin)
                                        VALUES(?, ?, ?, ?, ?)');
                    $rejectAmtCommentStmt->bind_param('sssss', $rejectAmtComment, $dateTime, $productId, $requestId, $admin);
                    $rejectAmtCommentStmt->execute();


                    $conn->commit();

                    $getTotalQuantityStmt = $conn->prepare('SELECT SUM(request_quan) AS totalQuan FROM requestdetail WHERE request_id = ? AND request_product_status = 0');
                    $getTotalQuantityStmt->bind_param('s', $requestId);
                    $getTotalQuantityStmt->execute();
                    $getTotalQuantityResult = $getTotalQuantityStmt->get_result();


                    writeToLog("\n" . funcGetDate('MY') . ": [$requestId] [$productId] - Merchandise rejected by [$admin]. Reason: $rejectReason.\n");

                    if (mysqli_num_rows($getTotalQuantityResult) > 0) {
                            $getTotalQuantity = $getTotalQuantityResult->fetch_assoc();
                            if (is_null($getTotalQuantity['totalQuan'])) {
                            $updateRequestStmt = $conn->prepare('UPDATE request
                                                    SET request_status = -1
                                                    WHERE request_id = ?');
                            $updateRequestStmt->bind_param('s', $requestId);
                            $conn->begin_transaction();
                            $updateRequestStmt->execute();
                            $rejectAmt = "Request is rejected due to all product(s) is rejected";

                            $assignAdminStmt = $conn->prepare('INSERT INTO requestassignment(request_id, admin_in_charge, comment, date)
                                                                VALUES (?, ?, ?, ?);');
                            $assignAdminStmt->bind_param('ssss', $requestId, $admin, $rejectAmt, $dateTime);
                            $assignAdminStmt->execute();

                            $conn->commit();
                            writeToLog("\n" . funcGetDate('MY') . ": [$requestId] - Request is rejected due to all product(s) is rejected. Request rejected by [$admin]\n");
                        }
                    }
                    $tempArray = ['returnCode' => 200, 'message' => 'Merchandise rejected'];
                    echo json_encode($tempArray);
                } catch (Error $e) {
                    $conn->rollback();
                    $tempArray = ['returnCode' => 500, 'message' =>"Error found: $e"];
                    writeToLog("\n" . funcGetDate('MY') . ": [Error found] - $e. [SQL] - " . mysqli_error($conn) . "\n");
                } catch (Exception $e) {
                    $conn->rollback();
                    $tempArray = ['returnCode' => 500, 'message' =>"Error found: $e"];
                    writeToLog("\n" . funcGetDate('MY') . ": [Error found] - $e. [SQL] - " . mysqli_error($conn) . "\n");
                }


            }

        } catch (Error $e) {
            echo $e;
            echo mysqli_error($conn);
        } catch (Exception $e) {
            echo $e;
            echo mysqli_error($conn);
        }
    }
?>
