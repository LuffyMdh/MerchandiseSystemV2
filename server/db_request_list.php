<?php
    include 'connect.php';
    $userID = $_SESSION['loggedin'];
    
    if (isset($_GET['requestType'])) {
        if ($_GET['requestType'] == 'normal') {
            
            $filter = $_GET['filter'];
            $offset = $_GET['offset'];
            
            $data = array();
            $data['html'] = 'check';
            try {

                if (empty($_GET['dateSort'])) {
                    $sortDate = "ORDER BY rq.modify_date DESC ";
                } else {
                    $sortDate = "ORDER BY createdDate " . $_GET['dateSort'] . " "; 
                }

                if (empty($_GET['searchTxt'])) {
                    $search = '';
                } else {   
                    $searchTxt = mysqli_real_escape_string($conn, $_GET['searchTxt']);
                    $search = " AND  rq.request_id LIKE  '$searchTxt%' ";
                }

                if ($filter != 1 && $filter != 0 &&  $filter != -1) {
                    $stmt =  $conn->prepare("SELECT rq.request_id, rq.request_date AS createdDate, rq.request_status, ml.mer_loc_name as location
                                                FROM request rq
                                                INNER JOIN merchandiselocation ml
                                                ON rq.mer_loc_id = ml.mer_loc_id
                                                WHERE rq.user_id = ? $search
                                                GROUP BY rq.request_id
                                                $sortDate
                                                LIMIT 11
                                                OFFSET ?;");
                    $stmt->bind_param("ss", $userID, $offset);
                } else {
                    $stmt =  $conn->prepare("SELECT rq.request_id, rq.request_date AS createdDate, rq.request_status, ml.mer_loc_name as location
                                                FROM request rq
                                                INNER JOIN merchandiselocation ml
                                                ON rq.mer_loc_id = ml.mer_loc_id
                                                WHERE rq.user_id = ?
                                                AND rq.request_status = ?
                                                GROUP BY rq.request_id
                                                $sortDate
                                                LIMIT 11
                                                OFFSET ?;");
                    $stmt->bind_param("sss", $userID, $filter, $offset);
                }
             
    
                $stmt->execute();
                $result = $stmt->get_result();
                $rowCount = mysqli_num_rows($result);
            
                if($rowCount > 0) {
                    $rowCounter = 0;
                    $display = '';
                    while (($request = $result->fetch_assoc()) && ($rowCounter < 10)) {
            
                        switch($request['request_status']) {
                            case '-1':
                                $requestStatus = 'Rejected';
                                break;
                            case '0':
                                $requestStatus = 'Pending';
                                break;
                            case '1':
                                $requestStatus = 'Accepted';
                                break;
                        }
            
                        $display .= '
                        <tr>
                            <td>' . $request['request_id'] . '</td>
                            <td>' . changeDate($request['createdDate']) . '</td>
                            <td>' . $request['location'] . '</td>
                            <td>' . $requestStatus . '</td>
                            <td>
                                <div class="dropdown">
                                    <a class="hidden-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></a>
                                    <div class="dropdown-menu dot-dropdown" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item request-popup view-popup" data-location="' . $request['location'] . '"  data-request-id="' . $request['request_id'] . '" data-request-status="' . $requestStatus . '" >View</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        
                        ';
                        $rowCounter++;
                        
    
                    }
                } else {
                    $display =  '
                    <tr>
                        <td align="center" colspan="5">No request found!</td>
                    </tr>
            
                    ';
                }
    
                
                $data['html'] = $display;
                $data['totalRow'] = $rowCount;
    
                
            } catch (Throwable $e) {
                $data['html'] = $e . ' ' . mysqli_error($conn);
            } catch (Error $e) {
                $data['html'] = $e . ' ' . mysqli_error($conn);
            } catch (Exception $e) {
                $data['html'] = $e . ' ' . mysqli_error($conn);
            }

            echo json_encode($data);

        } elseif ($_GET['requestType'] == 'admin') {
            if ($_GET['requestList'] == 'number') {
                $list = array();
                $tempArr = array();

                $pendingList = getList($conn, 0, '18446744073709551615', 0, $smgEmpTable);
                $acceptedList = getList($conn, 1, '18446744073709551615', 0, $smgEmpTable);
                $rejecetedList = getList($conn, -1, '18446744073709551615', 0, $smgEmpTable);
                $allList = getList($conn, 'all', '18446744073709551615', 0, $smgEmpTable);
    

                $tempArr['totalList'] = $pendingList['totalList'];
                $tempArr['totalPages'] = $pendingList['totalPages'];
                $list['pending'] = $tempArr;
    
                $tempArr['totalList'] = $acceptedList['totalList'];
                $tempArr['totalPages'] = $acceptedList['totalPages'];
                $list['accepted'] = $tempArr;

                $tempArr['totalList'] = $rejecetedList['totalList'];
                $tempArr['totalPages'] = $rejecetedList['totalPages'];
                $list['rejected'] = $tempArr;

                $tempArr['totalList'] = $allList['totalList'];
                $tempArr['totalPages'] = $allList['totalPages'];
                $list['all'] = $tempArr;


                
                
    
                echo json_encode($list);
            } else {
                $filter = $_GET['filter'];
                $offset = $_GET['offset'];
                $currentPage = $_GET['currentPage'];
                
                $list = getList($conn, $filter, $currentPage, $offset, $smgEmpTable);

                if ($list['totalList'] > 0) {
                    while ($listDetail  = $list['result']->fetch_assoc()) {
                        
                        switch($listDetail['request_status']) {
                            case '-1':
                                $requestStatus = '<span class="tbl-icon status-icon span-rejected"></span>Rejected';
                                break;
                            case '0':
                                $requestStatus = '<span class="tbl-icon status-icon span-pending"></span>Pending';
                                break;
                            case '1':
                                $requestStatus = '<span class="tbl-icon status-icon span-approved"></span>Accepted';
                                break;
                        }
                        
                        echo '
                        <tr>
                            <td>' . $listDetail['request_id'] . '</td>
                            <td>' . changeDate($listDetail['request_date']) . '</td>
                            <td>' . $listDetail['totalItem'] . '</td>
                            <td>' . $requestStatus . '</td>
                            <td>' . $listDetail['mer_loc_name'] . '</td>
                            <td>' .  $listDetail['name']. '</td>
                            <td>';
                                        echo '
                                        <a class="dropdown-item" id="id-btn-view" onclick="viewDetail(\'' . $listDetail['request_id'] . '\')"><i class="bi bi-eye"></i></a>
                                    
                            </td>
                        </tr>
                        ';
                    }
                } else {
                    echo '
                        <tr>
                            <td align="center" colspan="6">No request found!</td>
                        </tr>
                    ';
                }
                

            }

            
        }
    }


    function getList($conn, $filter, $currentPage, $offset, $smgEmpTable) {
        try {
            $reqList = array();
            if ($filter == 'all') {
                $adminReqStmt = $conn->prepare("SELECT req.request_id, req.request_date, IF(req.request_status = 0, IFNULL((SELECT SUM(request_quan) FROM requestdetail rd WHERE rd.request_id = req.request_id), 'Pending'), IF(req.request_status = 1, 'Accepted', 'Rejected')) AS totalItem, req.request_status, ml.mer_loc_name, us.name
                FROM request req
                LEFT JOIN $smgEmpTable as us
                ON req.user_id = us.email
                INNER JOIN merchandiselocation ml
                ON ml.mer_loc_id = req.mer_loc_id
                GROUP BY req.request_id
                ORDER BY req.modify_date DESC
                LIMIT ?
                OFFSET ?;");
                $adminReqStmt->bind_param('ss', $currentPage, $offset);
            } else {
                $adminReqStmt = $conn->prepare("SELECT req.request_id, req.request_date, IF(req.request_status = 0, IFNULL((SELECT SUM(request_quan) FROM requestdetail rd WHERE rd.request_id = req.request_id), 'Pending'), IF(req.request_status = 1, 'Accepted', 'Rejected')) AS totalItem, req.request_status, ml.mer_loc_name, us.name
                FROM request req
                LEFT JOIN $smgEmpTable as us
                ON req.user_id = us.email
                INNER JOIN merchandiselocation ml
                ON ml.mer_loc_id = req.mer_loc_id
                WHERE req.request_status = ?
                GROUP BY req.request_id
                ORDER BY req.modify_date DESC
                LIMIT ?
                OFFSET ?;");
                $adminReqStmt->bind_param('sss', $filter, $currentPage, $offset);
            }
            
            $adminReqStmt->execute();
            $reqResult = $adminReqStmt->get_result();
            $totalReq = mysqli_num_rows($reqResult);
            $totalPages = ceil($totalReq / 10);

            $reqList['totalList'] = $totalReq;
            $reqList['totalPages'] = $totalPages;
            $reqList['result'] = $reqResult;

            return $reqList;
            
        } catch (Exception $e) {
            return $e;
        }
    }

    
?>