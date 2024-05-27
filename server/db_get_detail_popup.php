<?php
    include 'connect.php';

    if (isset($_POST['data_requestId'])) {

        $requestId = $_POST['data_requestId'];
        // $requestStatus = $_POST['data_requestStatus'];
        $requestLocation = $_POST['data_location'];



        try {
            $getRequestStmt =  $conn->prepare("SELECT rq.request_id, rq.request_status, rq.request_purpose, rq.request_date as createdDate
                                        FROM request rq
                                        WHERE request_id = ?;");

            $getRequestStmt->bind_param("s", $requestId);
            $getRequestStmt->execute();
            $getRequestResult = $getRequestStmt->get_result();
            $rowCount = mysqli_num_rows($getRequestResult);

            if ($rowCount <= 0) {
                $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
                echo json_encode($returnArray);
                return;
            } else {
                $getRequest = $getRequestResult->fetch_assoc();
            }

            switch($getRequest['request_status']) {
                case '1':
                    $requestStatus = 'Accepted';
                    break;

                case '-1':
                    $requestStatus = 'Rejected';
                    break;

                case '0':
                    $requestStatus = 'Pending';
                    break;
                
                default:
                    $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
                    echo json_encode($returnArray);
                    return;
                    break;
            }

            if ($requestStatus != 'Pending') {
                $statusStmt = $conn->prepare("SELECT DATE(rs.date) AS statusDate, us.name, rs.comment, rs.pick_up_date AS pickupDate
                                                FROM requestassignment rs 
                                                JOIN $smgEmpTable as us
                                                WHERE rs.request_id = ? AND
                                                rs.admin_in_charge = us.email;");
    
                $statusStmt->bind_param("s", $requestId);
                $statusStmt->execute();
                $statusResult = $statusStmt->get_result();
                $statusDetail = $statusResult->fetch_assoc();
            }

        } catch (Error $e) {
            echo $e;
            echo mysqli_error($conn);
        } catch (Exception $e) {
            echo $e;
            echo mysqli_error($conn);
        }




        /*
        
        <p>Status: <span>' . $requestStatus . '</span></p>
        <p>In Charge: '; ?> <?php echo ($requestStatus != 'Pending') ? $statusDetail['name'] . ' (' . $statusDetail['statusDate'] . ')' : "Pending"; ?> <?php echo ' </p>
        <p>Comment: </p>*/

        if ($rowCount > 0) {
            echo '
            <div class="modal fade" id="viewPopup" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header flex-column align-items-start">
                            <h5 class="modal-title">Request ID: ' . $requestId .' (' . $requestLocation . ')</h5>
                            <div class="div-table flex-column d-flex">
                                <table class="table table-borderless tbl-request-detail tbl-request-status flex-fill">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Date:</th>
                                            <td>' . changeDate(splitDateTime($getRequest['createdDate'], 0)) . '</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Status:</th>
                                            <td>' . $requestStatus . '</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">In Charge:</th>
                                            <td>';?><?php echo ($requestStatus != 'Pending') ? $statusDetail['name'] . ' (' .  changeDate(splitDateTime($statusDetail['statusDate'], 0)) . ')' : "Pending"; ?><?php echo '</td>
                                        </tr>';

                                        if ($requestStatus == 'Accepted') {
                                            echo '
                                                <tr>
                                                    <th scope="row">Pickup Date:</th>
                                                    <td>' . changeDate(splitDateTime($statusDetail['pickupDate'], 0)) . '</td>
                                                </tr>
                                                <tr>
                                                    <th scope="row">Pickup Time:</th>
                                                    <td>' . substr(splitDateTime($statusDetail['pickupDate'], 1), 0, -3) . '</td>
                                                </tr>
                                                ';
                                        }
   
                                        echo '<tr>
                                            <th scope="row">Comment:</th>
                                            <td>'; ?><?php echo ($requestStatus != 'Pending') ? $statusDetail['comment'] : "Pending"; ?>  <?php echo '</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Purpose: </th>
                                            <td>' . $getRequest['request_purpose'] . '</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <table class="table table-borderless tbl-request-detail tbl-request-doc flex-fill">
                                    <tbody>
                                        <tr>
                                            <th scope="row">Support Document</th>
                                        </tr>
                                        <tr><td scope="row"><a href="#" onclick="downloadDoc(event, \'' . $requestId . '\')">Download</a></td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>


                        <div class="modal-body">
                            <div class="row">
                                <div class="merchandise-details">
                                    <div class="table-responsive-lg tbl-req tbl-popup">
                                        <table class="table">
                                            <thead>
                                                <tr>
                                                    <th scope="col">Merchandise</th>
                                                    <th scope="col">Group</th>
                                                    <th scope="col" class="quan-col">Quantity</th>
                                                    <th scope="col"></th>
                                                </tr>
                                            </thead>
                                            <tbody>';
                                            
                                            if ($requestStatus == 'Accepted') {
                                                
                                                try {
                                                    $getMerchandiseDetailStmt = $conn->prepare('SELECT pro.product_id, pro.product_name, pg.p_group_name, rd.request_quan 
                                                                                                FROM requestdetail rd
                                                                                                INNER JOIN product pro
                                                                                                ON pro.product_id = rd.product_id
                                                                                                INNER JOIN productgroupcategory pg
                                                                                                ON pro.p_group_id = pg.p_group_id
                                                                                                WHERE rd.request_id = ?');
                                                    $getMerchandiseDetailStmt->bind_param('s', $requestId);
                                                    $getMerchandiseDetailStmt->execute();
                                                    $getMerchandiseDetailResult = $getMerchandiseDetailStmt->get_result();
                                                    if ($getMerchandiseDetailResult->num_rows > 0) {
                                                        while ($getMerchandise = $getMerchandiseDetailResult->fetch_assoc()) {
                                                            
                                                            echo '
                                                                <tr>
                                                                    <td style="text-align: left; padding: 0 8px">' . $getMerchandise['product_name'] . '</td>
                                                                    <td>' . $getMerchandise['p_group_name'] . '</td>
                                                                    <td class="quan-col">x' . $getMerchandise['request_quan'] . '</td>
                                                                </tr>
                                                            ';
                                                        }
                                                    }
                                                    
                                                } catch (Throwable $e) {

                                                }


                                            } else if ($requestStatus == 'Rejected') {
                                                echo '
                                                    <tr>
                                                        <td colspan="4" style="color: red;">Request is rejected!</td>
                                                    </tr>
                                                ';
                                            } else if ($requestStatus == 'Pending') {
                                                echo '
                                                    <tr>
                                                        <td colspan="4">Request is still pending</td>
                                                    </tr>
                                                    
                                                ';
                                            }
                                                echo '
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary btn-gray-style" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>';
        }
    }
?>