<?php
    include 'connect.php';

    if (isset($_POST['code'])) {
        $productId = $_POST['code'];

        try {
            $productQuantityStmt = $conn->prepare('SELECT product_quan, mer_loc_id, product_location_status FROM productquantity WHERE product_id = ?');
            $productQuantityStmt->bind_param('s', $productId);
            $productQuantityStmt->execute();
            
            $productQuantityResult = $productQuantityStmt->get_result();
            $productQuantityArray = array();

            if (mysqli_num_rows($productQuantityResult) > 0) {
                while($productQuantity = $productQuantityResult->fetch_assoc()) {
                    $tempArray = array();
                    array_push($tempArray, $productQuantity['product_quan']);
                    array_push($tempArray, $productQuantity['product_location_status']);
                    $productQuantityArray[$productQuantity['mer_loc_id']] = $tempArray;
                }
                $productQuantityArray['statusCode'] = 200;
            }
        } catch (Throwable $e) {
            $productQuantityArray['statusCode'] = 400;
            $productQuantityArray['message'] = 'Error found: ' . $e . '. MySQL Error: ' . mysqli_error($conn);
        } catch (Exception $e) {
            $productQuantityArray['statusCode'] = 400;
            $productQuantityArray['message'] = 'Error found: ' . $e . '. MySQL Error: ' . mysqli_error($conn);
        } catch (Error $e) {
            $productQuantityArray['statusCode'] = 400;
            $productQuantityArray['message'] = 'Error found: ' . $e . '. MySQL Error: ' . mysqli_error($conn);
        }

        echo json_encode($productQuantityArray);
    } else {
        echo 'checking';
    }
?>