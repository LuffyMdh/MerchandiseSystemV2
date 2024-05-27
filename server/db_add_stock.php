<?php
    include 'connect.php';
    
    if(isset($_POST['code']) && isset($_POST['quan']) && isset($_POST['locationId']) && isset($_POST['locationName'])) {
        $productId = $_POST['code'];
        $addQuantity = $_POST['quan'];
        $locationId = $_POST['locationId'];
        $locationName = $_POST['locationName'];
        $resultArray = array();
        $admin = $_SESSION['loggedin'];

        if (is_numeric($addQuantity) != 1) {
            $resultArray['statusCode'] = 400;
            echo json_encode($resultArray);
        }

        if ($locationId != 1) {
            try {


                $conn->begin_transaction();

                $minusStockStmt = $conn->prepare('UPDATE productquantity
                                                    SET product_quan = product_quan -
                                                                            CASE
                                                                                WHEN product_quan >= ? AND (SELECT product_location_status FROM productquantity WHERE product_id= ? AND mer_loc_id = ?) = 1 THEN ?
                                                                                ELSE 0
                                                                            END
                                                    WHERE product_id = ? AND mer_loc_id = 1 AND product_location_status = 1');
                $minusStockStmt->bind_param('sssss', $addQuantity, $productId, $locationId,  $addQuantity, $productId);
                $minusStockStmt->execute();

                if ($conn->affected_rows > 0) {
                    $addStockStmt = $conn->prepare('UPDATE productquantity
                    SET product_quan = product_quan + ?
                    WHERE product_id = ? AND mer_loc_id = ?');
                    $addStockStmt->bind_param('sss', $addQuantity, $productId, $locationId);
                    $addStockStmt->execute();

                    $conn->commit();
                    $resultArray['statusCode'] = 200;
                    $resultArray['message'] = 'Successfully transfer ' . $productId . ' stock from Kuching to ' . $locationName . '. Quantity transferred:  ' . $addQuantity . ".";
                    writeToLog("\n" . funcGetDate('MY') . ": " . $resultArray['message'] . " This transaction is done by [$admin].\n");
                } else {
                    $conn->rollback();
                    $resultArray['statusCode'] = 401;
                    $resultArray['message'] = 'Transfer failed. Not enough quantity or location is inactived.';
                    writeToLog("\n" . funcGetDate('MY') . ": Transfer failed. Not enough quantity or location is inactived!. This transaction is done by [$admin].\n");
                    echo json_encode($resultArray);
                    return;
                }  

            } catch (Throwable $e) {
                $resultArray['message'] = $e . ' ' . mysqli_error($conn);
                $resultArray['statusCode'] = 500;
                $conn->query('ROLLBACK');
            } catch (Error $e) {
                $resultArray['message'] = $e . ' ' . mysqli_error($conn);
                $resultArray['statusCode'] = 500;
                $conn->query('ROLLBACK');
            } catch (Exception $e) {
                $resultArray['message'] = $e . ' ' . mysqli_error($conn);
                $resultArray['statusCode'] = 500;
                $conn->query('ROLLBACK');
            }

        } else {
            try {
                $conn->query('START TRANSACTION');
                $addStockStmt = $conn->prepare('UPDATE productquantity
                                                SET product_quan = product_quan + ?
                                                WHERE product_id = ? AND mer_loc_id = ?');
                $addStockStmt->bind_param('sss', $addQuantity, $productId, $locationId);
                $addStockStmt->execute();

                $conn->query('COMMIT');
                $resultArray['statusCode'] = 200;
                $resultArray['message'] = 'Successfully added ' . $productId . ' stock. Quantity added:  ' . $addQuantity . ".";
                writeToLog("\n" . funcGetDate('MY') . ": " . $resultArray['message'] . " This transaction is done by [$admin].\n");

            } catch (Throwable $e) {
                $resultArray['message'] = $e . ' ' . mysqli_error($conn);
                $resultArray['statusCode'] = 400;
                $conn->query('ROLLBACK');
            } catch (Error $e) {
                $resultArray['message'] = $e . ' ' . mysqli_error($conn);
                $resultArray['statusCode'] = 400;
                $conn->query('ROLLBACK');
            } catch (Exception $e) {
                $resultArray['message'] = $e . ' ' . mysqli_error($conn);
                $resultArray['statusCode'] = 400;
                $conn->query('ROLLBACK');
            }
        }
        echo json_encode($resultArray);
    }
?>