<?php
    include 'server/connect.php';
    include 'server/user_session.php';
    
    $current_page = 'admin_request_detail';
    if (isset($_GET['request_id']) && isset($_GET['status']) && isset($_GET['location'])) {
        $requestId = $_GET['request_id'];
        $statusCode = $_GET['status'];
        $locationCode = $_GET['location'];

        $offset = $_GET['status'];

        try {
            $getRequesterDetailStmt = $conn->prepare("SELECT us.name, us.email, us.empid, us.division, us.phonenumber, rq.request_status, rq.request_date, rq.request_purpose, rq.mer_loc_id, ml.mer_loc_name
                                                        FROM request rq
                                                        INNER JOIN merchandiselocation ml
                                                        ON ml.mer_loc_id = rq.mer_loc_id
                                                        INNER JOIN $smgEmpTable us
                                                        ON rq.user_id = us.email
                                                        WHERE rq.request_id = ? AND rq.request_status = ? AND rq.mer_loc_id = ?;");
            $getRequesterDetailStmt->bind_param('sss', $requestId, $statusCode, $locationCode);
            $getRequesterDetailStmt->execute();
            $getRequesterDetailResult = $getRequesterDetailStmt->get_result();

            if (mysqli_num_rows($getRequesterDetailResult) > 0) {
                $getRequesterDetail = $getRequesterDetailResult->fetch_assoc();
            } else {
                header('Location: admin_request.php');
            }

            $statusCode = $getRequesterDetail['request_status'];
            $requestLocation = $getRequesterDetail['mer_loc_id'];

            // $getMerchandiseResult = displayProduct($conn, $requestId, $requestLocation, $statusCode, $offset);

            // $merchandiseTotal = $getMerchandiseResult->num_rows;

            // $getRequestedProductStmt = $conn->prepare('SELECT pro.product_img, pro.product_id, pro.product_name, rd.request_quan, pg.p_group_name, pq.product_quan, rd.request_product_status, IF(pq.product_quan < rd.request_quan, 0, 1) AS proceedStatus, (SELECT rd_comment FROM requestdetailcomment WHERE request_id = ? AND product_id = rd.product_id LIMIT 1) AS rd_comment
            //                                                 FROM requestdetail rd
            //                                                 INNER JOIN product pro
            //                                                 ON pro.product_id = rd.product_id
            //                                                 INNER JOIN productgroupcategory pg
            //                                                 ON pro.p_group_id = pg.p_group_id
            //                                                 INNER JOIN productquantity pq
            //                                                 ON pq.product_id = pro.product_id
            //                                                 WHERE rd.request_id = ? AND pq.mer_loc_id = (SELECT mer_loc_id FROM request WHERE request_id = ?)
            //                                                 ORDER BY rd.request_product_status DESC, pg.p_group_id ASC;');
            // $getRequestedProductStmt->bind_param('sss', $requestId, $requestId, $requestId);
            // $getRequestedProductStmt->execute();
            // $getRequestedProductResult = $getRequestedProductStmt->get_result();



            // $getRequestDetailStmt = $conn->prepare('SELECT 	us.name, us.empid, us.division, us.email, us.phonenumber,
            //                                         req.request_id, req.request_date, req.request_purpose,
            //                                         pro.product_id, pro.product_quan, pro.product_img, pro.product_name, rd.request_quan
            //                                         FROM smg.tblemployee as us
            //                                         LEFT JOIN request as req 
            //                                         ON us.empid = req.user_id
            //                                         LEFT JOIN requestdetail as rd
            //                                         ON req.request_id = rd.request_id
            //                                         LEFT JOIN product as pro
            //                                         ON rd.product_id = pro.product_id
            //                                         WHERE req.request_id = ?
            //                                         AND req.request_status = ?;');
            // $getRequestDetailStmt->bind_param('ss', $requestId,  $statusCode);
            // $getRequestDetailStmt->execute();
            // $getRequestDetailResult = $getRequestDetailStmt->get_result();

            $getAdminInChargeStmt = $conn->prepare("SELECT us.name, ra.date, ra.pick_up_date, ra.comment
                                                        FROM $smgEmpTable AS us
                                                        INNER JOIN requestassignment ra
                                                        ON ra.admin_in_charge = us.email
                                                        WHERE request_id = ?;");
            $getAdminInChargeStmt->bind_param('s', $requestId);
            $getAdminInChargeStmt->execute();
            $getAdminInChargeResult = $getAdminInChargeStmt->get_result();
            $getAdminInCharge = $getAdminInChargeResult->fetch_assoc();

            
            // if (mysqli_num_rows($getRequestDetailResult) > 0) {
            //     $getRequestDetail = $getRequestDetailResult->fetch_assoc();

            //     $getAdminInChargeStmt->execute();
            //     $getAdminInChargeResult = $getAdminInChargeStmt->get_result();

            //     if(mysqli_num_rows($getAdminInChargeResult) > 0) {
            //         $getAdminInCharge = $getAdminInChargeResult->fetch_assoc();
            //     }


            // } else {
            //     header('Location: admin_request.php');
            // }
    
        } catch (Exception $e) {
            echo $e;
        } catch (Error $e) {
            echo $e;
            echo mysqli_error($conn);
        }
    } else {
        header('Location: admin_request.php');
    }

    // function displayProduct($conn, $requestId, $requestLocation, $statusCode, $offset) {
    //     if ($statusCode == 0) {
    //         $getMerchandiseStmt = $conn->prepare('SELECT pro.product_id, pro.product_name, pro.product_img, pq.product_quan, pg.p_group_name, rd.request_quan
    //                                                 FROM productquantity pq
    //                                                 INNER JOIN product pro
    //                                                 ON pro.product_id = pq.product_id
    //                                                 INNER JOIN productgroupcategory pg
    //                                                 ON pg.p_group_id = pro.p_group_id
    //                                                 LEFT JOIN requestdetail rd
    //                                                 ON rd.product_id = pq.product_id AND rd.request_id = ?
    //                                                 WHERE pq.mer_loc_id = ? AND pq.product_location_status = 1
    //                                                 ORDER BY pro.p_group_id
    //                                                 LIMIT 11
    //                                                 OFFSET ?;');
    //         $getMerchandiseStmt->bind_param('sss', $requestId, $requestLocation, $offset);
    //         $getMerchandiseStmt->execute();
    //         return $getMerchandiseStmt->get_result();
            
    //     } else if ($statusCode == 1) {
    //         $getMerchandiseStmt = $conn->prepare('SELECT pro.product_img, pro.product_name, rd.request_quan, pg.p_group_name
    //                                                 FROM product pro
    //                                                 INNER JOIN requestdetail rd
    //                                                 ON pro.product_id = rd.product_id
    //                                                 INNER JOIN productgroupcategory pg
    //                                                 ON pro.p_group_id = pg.p_group_id
    //                                                 WHERE rd.request_id = ?;');
    //         $getMerchandiseStmt->bind_param('s', $requestId);
    //         $getMerchandiseStmt->execute();
    //         return $getMerchandiseStmt->get_result();
    //     }
    // }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('default_html_head.php'); ?>
    <link href="style/style-admin-req-detail.css" rel="stylesheet">
    <link href="style/style-table.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="style/jquery.datetimepicker.css">

    <title>Merchandise | Request Details</title>
</head>
<body>
    <div id="id-overlay" class="overlay hidden">
        <div class="overlay-content" style="text-align: center;">
            <img src = "assets/img/gif/eating.svg" alt="Loading GIF"/>
            <p style="color: white; background-color: rgba(0,0,0, 0.6); padding: 10px;" id="id-overlay-message">Loading... summoning the power of zeros and ones.</p>    
        </div>
    </div>

    <!-- Popup Box -->
    <div class="modal modal-done-request fade" id="id-request-done" tabindex="-1" role="dialog" aria-labelledby="requestDoneLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered justify-content-center" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="swal-icon swal-icon--success">
                        <span class="swal-icon--success__line swal-icon--success__line--long"></span>
                        <span class="swal-icon--success__line swal-icon--success__line--tip"></span>

                        <div class="swal-icon--success__ring"></div>
                        <div class="swal-icon--success__hide-corners"></div>
                    </div>
                    <p class="items-list" id="p-done-message" style="text-align: center;">Request is sent!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-gray-style close-popup" data-bs-dismiss="modal" id="id-btn-close-popup">Close</button>  
                </div>
            </div>
        </div>
    </div>
    <!-- End Popup Box -->

    <!-- Reject Reason Popup  !-->
    <div class="modal fade" id="id-reject-reason" tabindex="-1" role="dialog" aria-labelledby="rejectReasonLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="id-reason-title">Rejection Reason</h5>
                </div>
                <div class="modal-body">
                    <textarea rows="6" style="resize: none;" id="id-txt-reject-reason-textarea" class="textarea-default-style" autofocus></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style" id="btn-close-box" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-default-style btn-reject" id="id-btn-reject-confirm">Reject</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Reject Reason Popup  !-->

    <!-- Add Merchandise Quantity Popup  !-->
    <div class="modal fade" id="id-add-amount" tabindex="-1" role="dialog" aria-labelledby="addAmountLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="id-merchandise-title"></h5>
                </div>
                <div class="modal-body">
                    <p id="id-p-merchandise">Add amount<span id="span-merchandise-qty"></span></p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control bar-default-style " placeholder="Amount" aria-label="Add Amount" id="id-txt-add-amt" autocomplete="off" onKeyPress="return isNumber(event)" aria-describedby="button-addon2">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-default-style btn-reject" id="id-btn-merchandise">Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add Merchandise Quantity Popup  !-->

    <!-- Remove Confirmation Popup  !-->
    <div class="modal fade" id="id-modal-remove-confirmation" tabindex="-1" role="dialog" aria-labelledby="removeConfirmationBoxLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p style="text-align: center; padding: 0; margin: 0;">Confirm remove?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style"  data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-default-style btn-reject" id="id-btn-remove-merchandise" onclick="removeMerchandise(event)">Remove</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Remove Confirmation Popup  !-->

    <!-- Merchandise Comment Popup  !-->
    <div class="modal fade" id="id-modal-comment" tabindex="-1" role="dialog" aria-labelledby="commentMerchandiseLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
            <div class="modal-content" style="height: 500px">
                <div class="modal-header">
                    <h5 class="modal-title">Merchandise Update Overview</h5>
                </div>
                <div class="modal-body" id="id-modal-body-comment" style="overflow: auto">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style" id="id-btn-close-comment" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Merchandise Comment Popup  !-->
    
    <!-- Start Confirm Date Picker -->
    <div class="modal fade" id="id-modal-date-picker" tabindex="-1" role="dialog" aria-labelledby="datePickerLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="text-align: center">
                <div class="modal-header">
                    <h5 class="modal-title" id="id-modal-date-confirm-title">Choose pick up date</h5>
                </div>
                <div class="modal-body">
                    <input id="datetimepicker" class="bar-default-style" type="text" autocomplete="off" style="text-align: center; color: var(--color-65-black); width: 100%" onKeyPress="return isNumber(event)" onclick="removeErrorInput(event)">
                    <div class="div-accept-comment" style="margin-top: 10px">
                        <label for="lbl-product-desc" style="text-align: left; color: var(--color-65-black);">Comment</label>
                        <textarea rows="6" style="resize: none;" id="id-txt-accept-reason-textarea" class="textarea-default-style" autofocus></textarea>
                    </div>

                </div>
                
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-gray-style" id="id-btn-cancel-date-picker" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-default-style btn-reject" id="id-btn-confirm-date">Accept Request</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Confirm Date Picker -->

    <!-- Reject Confirmation Popup  !-->
    <div class="modal fade" id="id-modal-alert-not-enough" tabindex="-1" role="dialog" aria-labelledby="notEnoughLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="width: 350px; margin: auto; align-items: center;">
                <div class="modal-body">
                    <p style="text-align: center; padding: 0; margin: 0;">Inventory Alert: Quantity Insufficiency!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style" id="id-btn-close-alert-not-enough" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Reject Confirmation Popup  !-->

    <!-- Multipurpose Alert Popup -->
    <div class="modal fade" id="id-box-mp-alert" aria-hidden="true" aria-labelledby="quantityErrorLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <p id="id-p-mp-alert" style="text-align: center;">Quantity not enough!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style btn-popup-style" id="id-btn-close-quantity-error" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Multipurpose Alert Popup -->
    
    <?php include ('header.php'); ?>

    <main>
        <?php include ('top_section.php'); ?>
        <section class="sect-admin-req-detail row">
            <div class="requestor-details col">
                <div class="requestor-detail">
                    <h6>Requested By</h6>
                    <div class="req-img">
                        <img src="assets/img/user-profile.jpg" alt="">
                    </div>
                    <p class="req-name" data-id="<?php echo $getRequesterDetail['empid']?>"><?php echo $getRequesterDetail['name'] ?></p>
                    <p class="req-unit"><?php echo $getRequesterDetail['division'] ?></p>
                    <p class="req-email"><?php echo $getRequesterDetail['email']?></p>
                    <p class="req-phone"><?php echo $getRequesterDetail['phonenumber'] ?></p>
                </div>
            </div>
            <div class="col">
                <div class="req-detail req-id-date row">
                    <div class="id-date col">
                        <table>
                            <tr>
                                <th>Request ID:</th><td><span class="span-req-id"><?php echo $requestId ?></span></td>
                            </tr>
                            <tr>
                                <th>Date: </th><td><span class="span-req-date"><?php echo changeDate($getRequesterDetail['request_date']) ?></span></td>
                            </tr>
                            <tr>
                                <th>Location: </th><td><span class="span-req-date"><?php echo $getRequesterDetail['mer_loc_name'] ?></span></td>
                            </tr>
                            <tr>
                                <th>Request Status: </th><td><?php echo getStatus($statusCode); ?> </td>
                            </tr>
                       
                            <?php  
                                if ($getAdminInChargeResult->num_rows > 0 && !is_null($getAdminInCharge['pick_up_date'])) {
                                    echo '<tr><th>Admin In Charge: </th><td>' . $getAdminInCharge['name'] . '</td></tr>';
                                    echo '<tr><th>Pick Up Date: </th><td>' . changeDate(splitDateTime($getAdminInCharge['pick_up_date'], 0)) . '</td></tr>';
                                    echo '<tr><th>Pick Up Time: </th><td>' . substr(splitDateTime($getAdminInCharge['pick_up_date'], 1), 0, -3) . '</td></tr>';
                                    echo '<tr><th>Comment: </th><td><textarea style="font-size: 0.8rem" rows="6" cols="30" disabled>' .  $getAdminInCharge['comment'] . '</textarea></td></tr>';
                                }

                            ?>
                            <tr>
                                <th>Purpose of Request: </th><td><textarea rows="3" cols="30" style="font-size: 0.8rem" disabled><?php echo $getRequesterDetail['request_purpose'] ?></textarea></td>
                            </tr>
                        </table>
                        <p>Support document <i class="bi bi-download" onclick="downloadDoc(event, '<?php echo $requestId  ?>', '<?php echo $getRequesterDetail['empid']  ?>')" style="margin-left: 5px; cursor:pointer"></i></p>                   
                    </div>

                    <div class="req-btn col"
                        <?php
                            if ($statusCode == 1 || $statusCode == -1) {
                                echo 'style="display: none"';    
                            } 
                        ?>
                    >
                        <button type="button" class="btn btn-primary btn-default-style" onclick="acceptRequest()">Accept</button>
                        <button type="button" class="btn btn-primary btn-gray-style" id="id-btn-request-reject">Reject</button>
                    </div>
                </div>




                <div class="req-detail req-items-details">
                    <div class="req-detail-header">
                    <?php
                        if ($statusCode == 0) {
                            $getGroupResult = getGroupCategory($conn);
                            echo '<div class="dropdown group-cate-location ">
                                    <button class="btn dropdown-toggle btn-dropdown" type="button" id="id-btn-dropdown-group" data-bs-toggle="dropdown" aria-haspopup="true" aria-expaded="false">
                                        Group
                                    </button>
                                    <div class="dropdown-menu dropdown-list dropdown-limit">';
                                
                                if ($getGroupResult->num_rows > 0) {
                                   
                                    while ($getGroup = $getGroupResult->fetch_assoc()) {
                                        echo '<a class="dropdown-item request-dropdown" data-groupid="' . $getGroup['p_group_id']  . '">' . $getGroup['p_group_name'] . '</a>';
                                    }
                                } else {
                                    echo 'No Location found!';
                                };
                                echo '</div></div>';
                        }
                    ?>
                    <h6>Merchandise</h6>
                    <div class="div-empty">

                    </div>
                    </div>

                    <div class="table-responsive-lg tbl-req">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th scope="col"></th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Quantity</th>
                                    <th scope="col">Group</th>
                                    <?php 
                                        if ($statusCode != -1) {

                                            if ($statusCode == 0) {
                                                echo '
                                                <th scope="col">Request Qty</th>
                                                <th scope="col">Action</th>
                                                ';
                                            }
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody id="id-table-tbody">
                                <?php 
                                    // if($statusCode == 0) {
                                    //     if ($getMerchandiseResult->num_rows > 0) {
                                    //         while ($getMerchandise = $getMerchandiseResult->fetch_assoc()) {
                                    //             echo '
                                    //             <tr>
                                    //                 <td><img src="' . $getMerchandise['product_img'] . '" alt="" width="40px" height="40px"></td>
                                    //                 <td class="td-product-name">' . $getMerchandise['product_name'] . '</td>
                                    //                 <td>' . $getMerchandise['product_quan'] . '</td>
                                    //                 <td>' . $getMerchandise['p_group_name'] . '</td>
                                    //                 <td>'; echo  (is_null($getMerchandise['request_quan'])) ?  'Not added' : $getMerchandise['request_quan']; echo '</td>
                                    //                 <td>
                                    //                     <div class="dropdown">
                                    //                         <a class="hidden-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></a>
                                    //                         <div class="dropdown-menu dot-dropdown" aria-labelledby="dropdownMenuButton">
                                    //                             <a class="dropdown-item '; echo (is_null($getMerchandise['request_quan'])) ? '" onclick="addProduct(event, \'' . $getMerchandise['product_id'] . '\', \'' . $requestId . '\', \'1\')"' : 'disabled"';  echo '>Add</a>
                                    //                             <a class="dropdown-item '; echo (is_null($getMerchandise['request_quan'])) ? 'disabled"' : '" onclick="addProduct(event, \'' . $getMerchandise['product_id'] . '\', \'' . $requestId . '\', \'2\')"';  echo '>Edit</a>
                                    //                             <a class="dropdown-item '; echo (is_null($getMerchandise['request_quan'])) ? 'disabled"' : '" onclick="addProduct(event, \'' . $getMerchandise['product_id'] . '\', \'' . $requestId . '\', \'3\')"';  echo '>Remove</a>
                                    //                         </div>
                                    //                     </div>
                                    //                 </td>
                                    //             </tr>
                                    //             ';
                                    //         }
                                    //     }
                                    // } else if ($statusCode == -1) {
                                    //     echo '
                                    //         <tr>
                                    //             <td colspan="4" style="color: red;">Request is rejected!</td>
                                    //         </tr>
                                    //     ';
                                    // } else if ($statusCode == 1) {
                                    //     if ($getMerchandiseResult->num_rows > 0) {
                                    //         while ($getMerchandise = $getMerchandiseResult->fetch_assoc()) {
                                    //             echo '
                                    //             <tr>
                                    //                 <td><img src="' . $getMerchandise['product_img'] . '" alt="" width="40px" height="40px"></td>
                                    //                 <td class="td-product-name">' . $getMerchandise['product_name'] . '</td>
                                    //                 <td>' . $getMerchandise['request_quan'] . '</td>
                                    //                 <td>' . $getMerchandise['p_group_name'] . '</td>
                                    //             </tr>
                                    //             ';
                                    //         }
                                    //     }
                                    // }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination">
                        <span id="id-pagination-previous" class="previous"><</span>
                        <span id="id-pagination-current" class="current">1</span>
                        <span id="id-pagination-next" data-nextpage="<?php echo ($merchandiseTotal > 10) ? '1' : '0' ?>" class="next">></span>
                    </div>
                </div>
            </div>
        </section>
    </main>
    
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    <script src="style/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/jquery.datetimepicker.full.min.js"></script>
    <script type="text/javascript" src="js/function.js"></script>
    <script type="text/javascript" src="js/admin_request_detail.js"></script>
    <?php include 'footer.php' ?>
</body>
</html>