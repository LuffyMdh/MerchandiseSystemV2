<?php
    include 'connect.php';

    if (isset($_POST['locationId']) && isset($_POST['productId'])) {
        $productId = $_POST['productId'];
        $locationId = $_POST['locationId'];
        $returnArray = array();

        try {
           
            $returnProductQuantityStmt = $conn->prepare('UPDATE productquantity pq, (SELECT product_id, product_quan FROM productquantity WHERE mer_loc_id = ? AND product_id = ?) pq2
                                                    SET pq.product_quan = pq.product_quan + pq2.product_quan
                                                    WHERE pq.mer_loc_id = 1 AND pq.product_id = ?;');
            $returnProductQuantityStmt->bind_param('sss', $locationId, $productId, $productId);

            $setNewProductQuanStmt = $conn->prepare("UPDATE productquantity
                                                        SET product_quan = 0
                                                        WHERE product_id = ? AND mer_loc_id = ?");
            $setNewProductQuanStmt->bind_param('ss', $productId, $locationId);
            

            $conn->begin_transaction();
            $returnProductQuantityStmt->execute();
            $setNewProductQuanStmt->execute();
            $conn->commit();
            $returnArray = ['returnCode' => 200, 'message' => "Successfully return product to HQ."];
            echo json_encode($returnArray);
            return;

        } catch (Exception $e) {
            $conn->rollback();
            $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
            echo json_encode($returnArray);
            return;
        } catch (Error $e) {
            $conn->rollback();
            $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
            echo json_encode($returnArray);
            return;
        }
       
    } else {
        return;
    }
?>