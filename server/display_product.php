<?php
    error_reporting(E_ERROR);
    include 'connect.php';
    $cartId = $_SESSION['cartId'];
    if (isset($_POST['code']) && isset($_POST['locationId']) && isset($_POST['groupCate'])) {
        $offset = $_POST['offset'];
        $locationId = $_POST['locationId'];
        $groupId = $_POST['groupCate'];
        $code = $_POST['code'];

        try {

            $resultMerhLocation = getMerchLocation($conn);
            $arrayMerchLocation = array();
    
            if (mysqli_num_rows($resultMerhLocation) > 0 ) {
                while ($merchLocation = $resultMerhLocation->fetch_assoc()) {
                   $arrayMerchLocation[$merchLocation['mer_loc_id']] = $merchLocation['mer_loc_name'];
                }
            }

            if (empty($_POST['searchTxt'])) {
                $searchTxt = '%';
            } else {
                $searchTxt = '%' . $_POST['searchTxt'] . '%';
            }

            if ($code == 'all') {
                $sqlProductCode = '';
            } else {
                $sqlProductCode = "AND pro.product_cate_id = ?";
            }

            if ($groupId == 'all') {
                $sqlGroupCode = "";
            } else {
                $sqlGroupCode = "AND pro.p_group_id = ?";
            }

            $stmt = $conn->prepare("SELECT pro.product_id, pro.product_name, pro.product_img, pg.p_group_name, pq.product_quan,  pc.cate_name
                        FROM product pro
                        LEFT JOIN productquantity pq 
                        ON pq.product_id = pro.product_id AND pq.mer_loc_id = ? AND pq.product_location_status = 1
                        INNER JOIN  productgroupcategory as pg
                        ON pro.p_group_id = pg.p_group_id
                        LEFT JOIN productcategory pc
                        ON pc.product_cate_id = pro.product_cate_id
                        WHERE pro.product_status = 1 $sqlProductCode $sqlGroupCode AND pro.product_name LIKE ? AND product_location_status = 1
                        GROUP BY pro.product_id 
                        ORDER BY pg.p_group_id
                        LIMIT 13
                        OFFSET ?;");

            if ($sqlProductCode != '') {
                if ($sqlGroupCode != '') {
                    $stmt->bind_param("sssssss",  $locationId, $cartId, $locationId, $code, $groupId, $searchTxt, $offset); 
                } else {
                    $stmt->bind_param("ssssss",  $locationId, $cartId, $locationId, $code, $searchTxt, $offset); 
                }
            } else {
                if ($sqlGroupCode != '') {
                    $stmt->bind_param("ssssss",  $locationId, $cartId, $locationId, $groupId, $searchTxt, $offset);
                } else {
                    $stmt->bind_param("sss",  $locationId, $searchTxt, $offset); 
                }
            }

        
       
            $stmt->execute();
            $result = $stmt->get_result();
    
            $rowCount = mysqli_num_rows($result);  



    
        $displayProduct = '';
        if($rowCount > 0) {
            $rowCounter = 0;
            while (($productCode = $result->fetch_assoc()) && ($rowCounter != 12)) {
                if (!is_null($productCode['product_quan'])) {
                    $itemStock = (empty($productCode['product_quan'])) ? 'Out of Stock' : 'In Stock: ' . $productCode['product_quan'];
                    $displayProduct .= '
                    <div class="col-sm-6 product">
                        <div class="div-product-img" style="height: 150px;">
                            <img src="' . $productCode['product_img'] . '" alt="">
                        </div>
                        <div class="div-product-detail">
                            <h5 class="product-header"><b>' . $productCode['product_name'] . '</b></h5>
                            <h5>' . $productCode['cate_name'] . '</h5>
                            <h5>' . $productCode['p_group_name'] . '</h5>
                            <div class="add-cart-btn">
                                <div class="spinner spinner-add-to-cart">
                                    
                                </div>
                                
                                '; 
    
                                // if (empty($productCode['quantity']) AND $itemStock != 'Out of Stock') {
                                //     $displayProduct .= '
                                //                         <button type="button" class="btn btn-default-style btn-addCart" data-code="' . $productCode['product_id'] . '" onclick="addItemToCart(event, \'' . $productCode['product_id'] . '\')">Add to cart</button>
                                                        
                                //                         ';
                                // } elseif ((empty($productCode['quantity']) && $itemStock == 'Out of Stock') || (!empty($productCode['quantity']) && $itemStock == 'Out of Stock')) {
                                //     $displayProduct .= '<button type="button" class="btn btn-gray-style btn-addCart disabled-all-btn" tabindex="-1" data-code="' . $productCode['product_id'] . '">Not available</button>';
                                // } else {
                                //     $displayProduct .= '<button type="button" class="btn btn-gray-style btn-addCart disabled-all-btn" tabindex="-1" data-code="' . $productCode['product_id'] . '">Already in Cart</button>';
                                 
                                // }
                                $displayProduct .= '</div>
                        </div>
                    </div>
                    ';
                    $rowCounter++;
                }
            }


        } else {
            $displayProduct = '';
        }
        $data = array();
        $data['html'] = $displayProduct;
        $data['totalPages'] = $rowCount;
        echo json_encode($data);

    } catch (Throwable $e) {
        $data = array();
        $data['error'] = $e . '. This is throwable';
        $data['mysqli_error'] = mysqli_error($conn);
        $data['html'] = '';
        echo json_encode($data);
        return;
    } catch (Error $e) {
        $data = array();
        $data['error'] = $e. '. This is Error';
        $data['mysqli_error'] = mysqli_error($conn);
        $data['html'] = '';
        echo json_encode($data);
        return;
    }
    }

/*
    $sqlAllProduct = "select * from product";
    $getAllProduct = $conn->query($sqlAllProduct);

    if ($getAllProduct->num_rows > 0) {
        while ($product = $getAllProduct->fetch_assoc()) {
            echo '
            <div class="col-sm-6 product">
                <div class="div-product-img">
                    <img src="' . $product['product_img'] . '" alt="">
                </div>
                <div class="div-product-detail">
                    <h5 class="product-header">' . $product['product_name'] . '</h5>
                    <p class="product-stock">In Stock: ' . $product['product_quan'] . '</p>
                    <div class="add-cart-btn">
                        <button type="button" class="btn btn-primary">Add to cart</button>
                    </div>
                    <a href="#">View Merchandise</a>
                </div>
            </div>
            ';
        }
    }*/
?>

