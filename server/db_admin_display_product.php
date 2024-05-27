<?php
    include 'connect.php';

    if (isset($_POST['cate']) && isset($_POST['offset'])) {
        $category = $_POST['cate'];
        $offset = $_POST['offset'];

        if (empty($_POST['searchTxt'])) {
            $search = '';
        } else {
            $searchTxt = mysqli_real_escape_string($conn, $_POST['searchTxt']);
            $search = " AND pro.product_name LIKE '%$searchTxt%' ";
        }

        $getProductResult = getProduct($conn, $category, $search, $offset);
        $totalRecord = 0;
        $totalRow = mysqli_num_rows($getProductResult);
        $displayHTML = '';
    
        if ( $totalRow > 0) {
            $counter  = 0;

            while (($getProduct = $getProductResult->fetch_assoc()) && ($counter < 10)) {
                $displayHTML = $displayHTML . '
                    <tr>
                        <td>
                            <div>
                                <input class="form-check-input product-checkbox" type="checkbox" id="id-product-checkbox" value="" data-code="' . $getProduct['product_id'] . '" onchange="addToCheckbox(event, \'' . $getProduct['product_id'] . '\')" aria-label="...">
                            </div>
                        </td>
                        <td><img src="' . $getProduct['product_img'] . '" alt="" width="40px" height="40px"></td>
                        <td>' . $getProduct['product_name'] . '</td>
                        <td>' . changeDate($getProduct['date']) . '</td>
                        <td>' . $getProduct['cate_name'] . '</td>
                        <td>' . $getProduct['p_group_name'] . '</td>
                        <td data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top<br>">' . $getProduct['totalQuantity'] . '</td>
                        <td>
                            <div class="dropdown">
                                <a class="hidden-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></a>
                                <div class="dropdown-menu dot-dropdown" aria-labelledby="dropdownMenuButton">
                                    <a class="dropdown-item" href="javascript:;" onclick="displayAddStock(event, \'' . $getProduct['product_id'] . '\',  \'' . $getProduct['product_name'] . '\')">Add Stock</a>
                                    <a class="dropdown-item" href="javascript:;" onclick="returnProduct(event, \'' . $getProduct['product_id'] . '\',  \'' . $getProduct['product_name'] . '\')">Return</a>
                                    <a class="dropdown-item" href="javascript:;" onclick="viewProduct(event, \'' . $getProduct['product_id'] . '\')">View</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                ';
                $counter++;
            }
        } else {
            $displayHTML = $displayHTML . '  <tr>
                        <td colspan="7" align="center">No item available!</td>
                    </tr>
            ';
        }

        $returnArray = ['returnCode' => 200, 'html' => $displayHTML, 'totalRecord' => $totalRow];
        echo json_encode($returnArray);
        return;
    } else {
        $displayHTML = $displayHTML . '  <tr>
                                            <td colspan="7" align="center">No item available!</td>
                                        </tr>
                                    ';
        $returnArray = ['returnCode' => 200, 'html' => $displayHTML];
        echo json_encode($returnArray);
        return;
    }

    function getProduct($conn, $category, $searchTxt, $offset) {
        try {
            if ($category == 'all') {
                $getProductStmt = $conn->prepare("SELECT pro.product_id, pro.product_img, pro.product_name, pro.date, cate.cate_name, pg.p_group_name, (SELECT SUM(product_quan) FROM productquantity pq WHERE pq.product_id = pro.product_id GROUP BY product_id) AS totalQuantity
                                                    FROM product pro
                                                    LEFT JOIN productcategory cate
                                                    ON pro.product_cate_id = cate.product_cate_id
                                                    LEFT JOIN productgroupcategory pg
                                                    ON pro.p_group_id = pg.p_group_id
                                                    WHERE pro.product_status = 1 $searchTxt
                                                    ORDER BY pro.p_group_id ASC
                                                    LIMIT 11
                                                    OFFSET ?;");
                $getProductStmt->bind_param('s', $offset);
            } else {
                $getProductStmt = $conn->prepare("SELECT pro.product_id, pro.product_img, pro.product_name, pro.date, cate.cate_name, pg.p_group_name, (SELECT SUM(product_quan) FROM productquantity pq WHERE pq.product_id = pro.product_id GROUP BY product_id) AS totalQuantity
                                                    FROM product pro
                                                    LEFT JOIN productcategory cate
                                                    ON pro.product_cate_id = cate.product_cate_id
                                                    LEFT JOIN productgroupcategory pg
                                                    ON pro.p_group_id = pg.p_group_id
                                                    WHERE cate.product_cate_id = ? $searchTxt
                                                    AND pro.product_status = 1
                                                    ORDER BY pro.p_group_id
                                                    LIMIT 11
                                                    OFFSET ?;");
                $getProductStmt->bind_param('ss', $category, $offset);
            }

            $getProductStmt->execute();
            $result = $getProductStmt->get_result();
            return $result;

        } catch (Exception $e) {
            echo 'Exception found: ' . $e;
            echo mysqli_error($conn);
        } catch (Error $e) {
            echo 'Error found: ' . $e;
            echo 'Mysqli Error' . mysqli_error($conn);
        }
    }
?>