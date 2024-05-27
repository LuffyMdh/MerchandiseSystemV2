<?php
    include 'connect.php';

    if (isset($_POST['removeType'])) {
        $removeType = $_POST['removeType'];
        $columnName = '';
        $firstCol = '';
        $secCol = '';
        $returnArray = array();


        try {
            switch ($removeType) {
                case '1':
                    $columnName = 'product_cate_id, cate_name';
                    if ($columnName == 'product_cate_id, cate_name') {
                        $getCGLStmt = $conn->prepare("SELECT $columnName
                                                        FROM productcategory");
                        $getCGLStmt->execute();
                        $getCGLResult = $getCGLStmt->get_result();
                        $firstCol = 'product_cate_id';
                        $secCol = 'cate_name';
                    } else {
                        return;
                    }

                    break;
    
                case '2':
                    $columnName = 'p_group_id, p_group_name';
                    if ($columnName == 'p_group_id, p_group_name') {
                        $getCGLStmt = $conn->prepare("SELECT $columnName
                                                        FROM productgroupcategory");
                        $getCGLStmt->execute();
                        $getCGLResult = $getCGLStmt->get_result();
                        $firstCol = 'p_group_id';
                        $secCol = 'p_group_name';
                    } else {
                        return;
                    }
                    break;
    
                case '3':
                    $columnName = 'mer_loc_id, mer_loc_name';
                    if ($columnName == 'mer_loc_id, mer_loc_name') {
                        $getCGLStmt = $conn->prepare("SELECT $columnName
                                                        FROM merchandiselocation");
                        $getCGLStmt->execute();
                        $getCGLResult = $getCGLStmt->get_result();
                        $firstCol = 'mer_loc_id';
                        $secCol = 'mer_loc_name';
                    } else {
                        return;
                    }
                    break;
    
                default:
                    return;
            }
    
            if (isset($getCGLResult) && $getCGLResult->num_rows > 0) {
                $tempArray = array();
                while ($getCGL = $getCGLResult->fetch_assoc()) {
                    $reallyTempArray = [$getCGL[$firstCol] => $getCGL[$secCol]];
                    array_push($tempArray, $reallyTempArray);
                }
    
                $returnArray = ['returnCode' => 200, 'list' => $tempArray];
                echo json_encode($returnArray);
                return;
            } else {
                $returnArray = ['returnCode' => 400, 'list' => 'No list found!'];
                echo json_encode($returnArray);
                return;
            }
            

        } catch (Error $e) {
            $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQLI Error: " . mysqli_error($conn) . "." ];
            echo json_encode($returnArray);
            return;
        } catch (Exception $e) {
            $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQLI Error: " . mysqli_error($conn) . "." ];
            echo json_encode($returnArray);
            return;
        }


    } else {
        return;
    }
?>