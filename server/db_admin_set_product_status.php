<?php
    include 'connect.php';

    if (isset($_POST['productStatus']) && isset($_POST['productId']) && isset($_POST['locationId'])) {
        $productId = $_POST['productId'];
        $productStatus = $_POST['productStatus'];
        $locationId = $_POST['locationId'];
        $admin = $_SESSION['loggedin'];

        $resultArray = array();

        try {
            $conn->begin_transaction();
            $updateProductStatusStmt = $conn->prepare('UPDATE productquantity SET product_location_status = ? WHERE product_id = ? AND mer_loc_id = ?');
            $updateProductStatusStmt->bind_param('sss', $productStatus, $productId, $locationId);
            $updateProductStatusStmt->execute();

            $updateRequestStatusStmt = $conn->prepare('UPDATE request
                                                        SET request_status = -1
                                                        WHERE request_id IN (SELECT rq.request_id
                                                                                    FROM request rq
                                                                                    INNER JOIN requestdetail rd
                                                                                    ON rq.request_id = rd.request_id
                                                                                    WHERE rd.product_id = ? AND rq.mer_loc_id = ? AND rq.request_status = 0)');
            $updateRequestStatusStmt->bind_param('ss', $productId, $locationId);
            

            $updateRequestProductStmt = $conn->prepare('UPDATE requestdetail
                                                            SET request_product_status = -1
                                                            WHERE request_id IN (SELECT rq.request_id
                                                                                    FROM request rq
                                                                                    INNER JOIN requestdetail rd
                                                                                    ON rq.request_id = rd.request_id
                                                                                    WHERE rd.product_id = ? AND rq.mer_loc_id = ? AND rq.request_status = 0)');
            $updateRequestProductStmt->bind_param('ss', $productId, $locationId);
            

            $getAffectedRequestStmt = $conn->prepare('SELECT rq.request_id
                                                        FROM request rq
                                                        INNER JOIN requestdetail rd
                                                        ON rq.request_id = rd.request_id
                                                        WHERE rd.product_id = ? AND rq.mer_loc_id = ? AND rq.request_status = 0');
            $getAffectedRequestStmt->bind_param('ss', $productId, $locationId);
            $getAffectedRequestStmt->execute();
            $getAffectedRequestResult = $getAffectedRequestStmt->get_result();



            if ($getAffectedRequestResult->num_rows > 0) {
                $insertIntoAssignmentStmt = $conn->prepare('INSERT INTO requestassignment(request_id, admin_in_charge, comment, date)
                                                                            VALUES(?, ?, ?, ?);');
                $insertIntoAssignmentStmt->bind_param('ssss', $requestId, $admin, $rejectedMessage, $dateTime);
                $rejectedMessage = 'Request is rejected due to one of the merchandise is not available/inactive';
                while ($getAffectedRequest = $getAffectedRequestResult->fetch_assoc()) {
                    $requestId = $getAffectedRequest['request_id'];
                    $insertIntoAssignmentStmt->execute();
                }
            }
            
            $updateRequestProductStmt->execute();
            $updateRequestStatusStmt->execute();
            

            $conn->commit();

            $resultArray['message'] = 'Change status on product: ' . $productId . ' to ' . $productStatus;
            $resultArray['statusCode'] = 200;
            writeToLog("\n" . funcGetDate('MY') . ": Product [$productId] is set to $productStatus. This transaction is done by [$admin]\n");

        } catch (Error $e) {
            $resultArray['message'] = 'Error found: ' . $e . '. MySQLI Error: ' . mysqli_error($conn);
            $resultArray['statusCode'] = 500;
            $conn->rollback();
            writeToLog("\n" . funcGetDate('MY') . ": " . $resultArray['message'] . "\n");
        } catch (Exception $e) {
            $resultArray['message'] = 'Error found: ' . $e . '. MySQLI Error: ' . mysqli_error($conn);
            $resultArray['statusCode'] = 500;
            $conn->rollback();
            writeToLog("\n" . funcGetDate('MY') . ": " . $resultArray['message'] . "\n");
        } catch (Throwable $e) {
            $resultArray['message'] = 'Error found: ' . $e . '. MySQLI Error: ' . mysqli_error($conn);
            $resultArray['statusCode'] = 500;
            $conn->rollback();
            writeToLog("\n" . funcGetDate('MY') . ": " . $resultArray['message'] . "\n");
        }
        echo json_encode($resultArray);

    } else {
        header('Location: ../admin_merchandise.php');
    }