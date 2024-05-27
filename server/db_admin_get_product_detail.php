<?php
    include 'connect.php';

    if (isset($_POST['code'])) {
        $productId = $_POST['code'];
        $productDetailArray = array();
        try {
           
            $getProductDetailStmt = $conn->prepare('SELECT product_id, product_name, product_desc, product_img, product_cate_id, p_group_id
                                                        FROM product
                                                        WHERE product_id = ?;');
            $getProductDetailStmt->bind_param('s', $productId);
            $getProductDetailStmt->execute();
            $getProductDetailResult = $getProductDetailStmt->get_result();

            if ($getProductDetailResult->num_rows > 0) {
                $getProductDetail = $getProductDetailResult->fetch_assoc();
            

                $productDetailArray['id'] = $getProductDetail['product_id'];
                $productDetailArray['name'] = $getProductDetail['product_name'];
                $productDetailArray['desc'] = $getProductDetail['product_desc'];
                $productDetailArray['img'] =  $getProductDetail['product_img'];
                $productDetailArray['cate_id'] = $getProductDetail['product_cate_id'];
                $productDetailArray['group_id'] = $getProductDetail['p_group_id'];

                $getProductQuantityStmt = $conn->prepare('SELECT mer_loc_id, product_quan, product_location_status FROM productquantity WHERE product_id = ?');
                $getProductQuantityStmt->bind_param('s', $productId);
                $getProductQuantityStmt->execute();
                $getProductQuantityResult = $getProductQuantityStmt->get_result();
               
                if ($getProductQuantityResult->num_rows > 0) {
                    while($getProductQuantity = $getProductQuantityResult->fetch_assoc()) {
                        $tempArray = array();
                        array_push($tempArray, $getProductQuantity['product_quan']);
                        array_push($tempArray, $getProductQuantity['product_location_status']);
                        $productDetailArray[$getProductQuantity['mer_loc_id']] = $tempArray;
                    }
                }
                
                $productDetailArray['statusCode'] = 200;
                $productDetailArray['message'] = 'Successfully retrieve product details.';
            }

           

        } catch (Error $e) {
            $productDetailArray['statusCode'] = 400;
            $productDetailArray['message'] = 'Error found: ' . $e . ' ' . mysqli_error($conn);
        } catch (Exception $e) {
            $productDetailArray['statusCode'] = 400;
            $productDetailArray['message'] = 'Error found: ' . $e . ' ' . mysqli_error($conn);
        } catch (Throwable $e) {
            $productDetailArray['statusCode'] = 400;
            $productDetailArray['message'] = 'Error found: ' . $e . ' ' . mysqli_error($conn);

        }
        echo json_encode($productDetailArray);
        return;
        
    }
    
?>