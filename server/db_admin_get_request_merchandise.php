<?php 
    error_reporting(E_ERROR); 
    include 'connect.php';
    if (isset($_POST['requestId']) && isset($_POST['statusCode']) && isset($_POST['locationCode']) && isset($_POST['offset']) && isset($_POST['groupId'])) {
        $requestId = $_POST['requestId'];
        $statusCode = $_POST['statusCode'];
        $requestLocation = $_POST['locationCode'];
        $offset = $_POST['offset'];
        $groupId = $_POST['groupId'];
        $displayHTML = '';

        try {
            if ($statusCode == 0) {

                if ($groupId != '') {
                    $stmtGroupId = " AND pro.p_group_id = ?";
                } else {
                    $stmtGroupId = '';
                }

                $getMerchandiseStmt = $conn->prepare("SELECT pro.product_id, pro.product_name, pro.product_img, pq.product_quan, pg.p_group_name, rd.request_quan
                                                        FROM productquantity pq
                                                        INNER JOIN product pro
                                                        ON pro.product_id = pq.product_id AND pro.product_status = 1
                                                        INNER JOIN productgroupcategory pg 
                                                        ON pg.p_group_id = pro.p_group_id 
                                                        LEFT JOIN requestdetail rd
                                                        ON rd.product_id = pq.product_id AND rd.request_id = ?
                                                        WHERE pq.mer_loc_id = ? AND pq.product_location_status = 1 $stmtGroupId
                                                        ORDER BY request_quan DESC, pro.p_group_id ASC
                                                        LIMIT 11
                                                        OFFSET ?;");

                if ($groupId != '') {
                    $getMerchandiseStmt->bind_param('ssss', $requestId, $requestLocation, $groupId, $offset);
                } else {
                    $getMerchandiseStmt->bind_param('sss', $requestId, $requestLocation, $offset);
                }

                
                $getMerchandiseStmt->execute();
                $getMerchandiseResult = $getMerchandiseStmt->get_result();
                
                if ($getMerchandiseResult->num_rows > 0) { 
                    $counter = 0;
                    while (($getMerchandise = $getMerchandiseResult->fetch_assoc()) && ($counter < 10)) { 
                        $counter++;
                        $displayHTML = $displayHTML . '
                        <tr>
                            <td><img src="' . $getMerchandise['product_img'] . '" alt="" width="40px" height="40px"></td>
                            <td class="td-product-name">' . $getMerchandise['product_name'] . '</td>
                            <td>' . $getMerchandise['product_quan'] . '</td>
                            <td>' . $getMerchandise['p_group_name'] . '</td>
                            <td>' .  ((is_null($getMerchandise['request_quan'])) ?  'Not added' : $getMerchandise['request_quan']) . '</td>
                            <td>
                                <div class="dropdown">
                                    <a class="hidden-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></a>
                                    <div class="dropdown-menu dot-dropdown" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item ' . ((is_null($getMerchandise['request_quan'])) ? '" onclick="addProduct(event, \'' . $getMerchandise['product_id'] . '\', \'' . $requestId . '\', \'1\')"' : 'disabled"') . '>Add</a>
                                        <a class="dropdown-item ' . ((is_null($getMerchandise['request_quan'])) ? 'disabled"' : '" onclick="addProduct(event, \'' . $getMerchandise['product_id'] . '\', \'' . $requestId . '\', \'2\')"') . '>Edit</a>
                                        <a class="dropdown-item ' . ((is_null($getMerchandise['request_quan'])) ? 'disabled"' : '" onclick="addProduct(event, \'' . $getMerchandise['product_id'] . '\', \'' . $requestId . '\', \'3\')"') . '>Remove</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        ';
                    }
                } else {
                    $displayHTML = $displayHTML . '
                        <tr>
                            <td colspan="6">No merchandise found!</td>
                        </tr>
                    ';
                }
            
            } else if ($statusCode == 1) {
                $getMerchandiseStmt = $conn->prepare('SELECT pro.product_img, pro.product_name, rd.request_quan, pg.p_group_name
                                                        FROM product pro
                                                        INNER JOIN requestdetail rd
                                                        ON pro.product_id = rd.product_id
                                                        INNER JOIN productgroupcategory pg
                                                        ON pro.p_group_id = pg.p_group_id
                                                        WHERE rd.request_id = ?;');
                $getMerchandiseStmt->bind_param('s', $requestId);
                $getMerchandiseStmt->execute();
                $getMerchandiseResult = $getMerchandiseStmt->get_result();
                if ($getMerchandiseResult->num_rows > 0) {
                    $counter = 0;
                    while (($getMerchandise = $getMerchandiseResult->fetch_assoc()) && ($counter < 10)) { 
                        $counter++;
                        $displayHTML = $displayHTML . '
                        <tr>
                            <td><img src="' . $getMerchandise['product_img'] . '" alt="" width="40px" height="40px"></td>
                            <td class="td-product-name">' . $getMerchandise['product_name'] . '</td>
                            <td>' . $getMerchandise['request_quan'] . '</td>
                            <td>' . $getMerchandise['p_group_name'] . '</td>
                        </tr>
                        ';
                    }
                }
            } else if ($statusCode == -1) {
                $displayHTML = $displayHTML . '
                            <tr>
                                <td style="color: red;" colspan="4">Request is rejected!</td>
                            </tr>';
            }
            
            $returnArray = ['returnCode' => 200, 'html'=> $displayHTML, 'totalProduct' => $getMerchandiseResult->num_rows];
            echo json_encode($returnArray);
            return;
        } catch (Throwable $e) {
            $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
            echo json_encode($returnArray);
            return;
        }
    }
?>