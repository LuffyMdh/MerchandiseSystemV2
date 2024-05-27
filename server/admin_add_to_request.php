<?php
    error_reporting(E_ERROR);
    include 'connect.php';

    if (isset($_POST['requestId']) && isset($_POST['productId']) && isset($_POST['amount']) && isset($_POST['operationType'])) {
        $requestId = $_POST['requestId'];
        $productId = $_POST['productId'];
        $amount = $_POST['amount'];
        $operationType = $_POST['operationType'];
        
        switch ($operationType) {
            case 1:

                if ($amount == '') {
                    $returnArray = ['returnCode' => 400, 'message' => "Invalid amount!"];
                    echo json_encode($returnArray);
                    return;
                }

                try {

                    $getProductQuantityStmt = $conn->prepare('SELECT IF(pq.product_quan >= ?, 1, 0) AS productStatus
                                                                FROM productquantity pq
                                                                WHERE product_id = ? AND mer_loc_id = (SELECT mer_loc_id FROM request WHERE request_id = ?) AND product_location_status = 1;');
                    $getProductQuantityStmt->bind_param('sss', $amount, $productId, $requestId);
                    $getProductQuantityStmt->execute();
        
                    $getProductQuantityResult = $getProductQuantityStmt->get_result();
        
                    if ($getProductQuantityResult->num_rows > 0) {
                        $getProductQuantity = $getProductQuantityResult->fetch_assoc();
                        $getProductStatus = $getProductQuantity['productStatus'];
        
                        if ($getProductStatus == '0') {
                            $returnArray = ['returnCode' => 400, 'message' => "Not enough quantity!"];
                            echo json_encode($returnArray);
                            return;
                        }
        
                    } else {
                        $returnArray = ['returnCode' => 404, 'message' => "Product is not available"];
                        echo json_encode($returnArray);
                        return;
                    }
        
        
                    $insertIntoRequestStmt = $conn->prepare('INSERT INTO requestdetail(request_id, product_id, request_quan, request_product_status)
                                                                VALUES(?, ?, ?, 0);');
                    $insertIntoRequestStmt->bind_param('sss', $requestId, $productId, $amount);
        
        
                    $conn->begin_transaction();
                        $insertIntoRequestStmt->execute();
        
                    $conn->commit();
        
        
                    $returnArray = ['returnCode' => 200, 'message' => "[$productId][$requestId][$amount] - Successfully added into request "];
                    writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message'] . ". This transaction is done by [" . $_SESSION['loggedin'] . "]\n");
                    echo json_encode($returnArray);
                    return;
        
                } catch (Throwable $e) {
                    $conn->rollback();
                    $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
                    writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message'] . ". This transaction is done by [" . $_SESSION['loggedin'] . "]\n");
                    echo json_encode($returnArray);
                    return;
                }
                break;

            case 2:
                if ($amount == '') {
                    $returnArray = ['returnCode' => 400, 'message' => "Invalid amount!"];
                    echo json_encode($returnArray);
                    return;
                }

                try {
                    $updateRequestMerchandiseStmt = $conn->prepare('UPDATE requestdetail
                                                                        SET 
                                                                        request_quan = CASE WHEN ? > 0 AND ? <= (SELECT product_quan FROM productquantity WHERE product_id = ? AND request_id = ? AND mer_loc_id = (SELECT mer_loc_id FROM request WHERE request_id = ?)) THEN ? ELSE (SELECT request_quan FROM requestdetail WHERE product_id = ? AND request_id = ?) END
                                                                        WHERE product_id = ? AND request_id = ?;');
                    $updateRequestMerchandiseStmt->bind_param('ssssssssss', $amount, $amount, $productId, $requestId, $requestId, $amount, $productId, $requestId, $productId, $requestId);
                 
        
                    $conn->begin_transaction();
                        $updateRequestMerchandiseStmt->execute();
        
                    $conn->commit();

                    if ($updateRequestMerchandiseStmt->affected_rows == 1) {
                        $returnArray = ['returnCode' => 200, 'message' => "[$productId][$requestId][$amount] - Successfully added into request. "];
                        writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message'] . " This transaction is done by [" . $_SESSION['loggedin'] . "]\n");
                        echo json_encode($returnArray);
                        return;
                    } else {
                        $returnArray = ['returnCode' => 400, 'message' => "Not enough quantity!"];
                        echo json_encode($returnArray);
                        return;
                    }
        
                } catch (Throwable $e) {
                    $conn->rollback();
                    $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
                    writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message'] . ". This transaction is done by [" . $_SESSION['loggedin'] . "]\n");
                    echo json_encode($returnArray);
                    return;
                }
                break;
            
                case 3:
                    try {
                        $removeMerchandiseStmt = $conn->prepare('DELETE FROM requestdetail WHERE product_id = ? AND request_id = ?');
                        $removeMerchandiseStmt->bind_param('ss', $productId, $requestId);

                        $conn->begin_transaction();
                            $removeMerchandiseStmt->execute();
                        $conn->commit();
                        $returnArray = ['returnCode' => 200, 'message' => "Successfully removed!"];
                        echo json_encode($returnArray);
                        return;
                    } catch (Throwable $e) {
                        $conn->rollback();
                        $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
                        writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message'] . ". This transaction is done by [" . $_SESSION['loggedin'] . "]\n");
                        echo json_encode($returnArray);
                        return;
                    }


                    break;

            default:
                return;
        }
    


    }
?>