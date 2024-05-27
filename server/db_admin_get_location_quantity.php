<?php
    include 'connect.php';

    if (isset($_POST['productId'])) {
        $productId = $_POST['productId'];
        $returnArray = array();

        try {
            $getProductQuantityLocationStmt = $conn->prepare('SELECT ml.mer_loc_id, ml.mer_loc_name, pq.product_quan
                                                            FROM merchandiselocation ml
                                                            LEFT JOIN productquantity pq
                                                            ON ml.mer_loc_id = pq.mer_loc_id
                                                            WHERE product_id = ?');
            $getProductQuantityLocationStmt->bind_param('s', $productId);
            $getProductQuantityLocationStmt->execute();
            $getProductQuantityLocationResult = $getProductQuantityLocationStmt->get_result();

            if ($getProductQuantityLocationResult->num_rows > 0) {
                $tempArray = array();
                while($getProductQuantityLocation = $getProductQuantityLocationResult->fetch_assoc()) {
                    array_push($tempArray, [$getProductQuantityLocation['mer_loc_id'] => $getProductQuantityLocation['mer_loc_name'], $getProductQuantityLocation['product_quan']]);
                }
                $returnArray = ['returnCode' => 200, 'message' => $tempArray];
                echo json_encode($returnArray);
                return;
            } else {
                $returnArray = ['returnCode' => 500, 'message' => 'List not found!'];
                echo json_encode($returnArray);
                return;
            }
        } catch (Exception $e) {
            $returnArray = ['returnCode' => '500', 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
            echo json_encode($returnArray);
            return;
        } catch (Error $e) {
            $returnArray = ['returnCode' => '500', 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
            echo json_encode($returnArray);
            return;
        }


    } else {
        return;
    }

?>