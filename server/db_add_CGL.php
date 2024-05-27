<?php
    include 'connect.php';
    $admin = $_SESSION['loggedin'];
    $resultArray = array();

    if (isset($_POST['addCode'])) {
        if (isset($_POST['name'])) {
            $addCode = $_POST['addCode'];
            $name = $_POST['name'];
            $isInsert = false;
           

            try {
                $everyProductArray = array();
                $conn->begin_transaction();
               
                switch ($addCode) {
                    case '2':
                        $uniqueId = createUniqueId('PC');
                        $insertCategoryGroupStmt = $conn->prepare('INSERT INTO productcategory(product_cate_id, cate_name)
                                                        VALUES(?, ?)');
                        $insertCategoryGroupStmt->bind_param('ss', $uniqueId, $name);
                        $isInsert = true;
                        break;
                    case '3':
                        $insertCategoryGroupStmt = $conn->prepare('INSERT INTO productgroupcategory(p_group_name)
                                                                                VALUES(?)');
                        $insertCategoryGroupStmt->bind_param('s', $name);
                        $isInsert = true;
                        break;
        
                    case '4':
                        
                        $insertCategoryGroupStmt = $conn->prepare('INSERT INTO merchandiselocation(mer_loc_name)
                                                                        VALUES(?)');
                        $insertCategoryGroupStmt->bind_param('s', $name);

                        $getProductQuantityStmt = $conn->prepare('SELECT (mer_loc_id+1) as nextId FROM merchandiselocation ORDER BY mer_loc_id DESC LIMIT 1');
                        $getProductQuantityStmt->execute();
                        $getProductQuantityResult = $getProductQuantityStmt->get_result();
                        
                        if ($getProductQuantityResult->num_rows == 1) {
                            
                            $getProductQuantity = $getProductQuantityResult->fetch_assoc();
                            $latestMerLocId = $getProductQuantity['nextId'];

                            $getEveryProductStmt = $conn->prepare('SELECT product_id FROM product');
                            $getEveryProductStmt->execute();
                            $getEveryProductResult = $getEveryProductStmt->get_result();

                            
                            if ($getEveryProductResult->num_rows > 0) {
                                
                                
                                while ($getEveryProduct = $getEveryProductResult->fetch_assoc()) {
                                    array_push($everyProductArray, $getEveryProduct['product_id']);
                                    
                                }
                                $insertIntoProductQuantityStmt = $conn->prepare('INSERT INTO productquantity(product_id, mer_loc_id, product_quan, product_location_status)
                                                                                    VALUES(?, ?, 0, 0)');
                                $insertIntoProductQuantityStmt->bind_param('ss', $proQuanId, $latestMerLocId);
          

                            } else {
                                $resultArray = ['returnCode' => 500, 'message' => 'Table productquantity is not returning any row OR not returning correct value.'];
                                echo json_encode($resultArray);
                                return;
                            }

                        } else {
                            $resultArray = ['returnCode' => 500, 'message' => 'Table productquantity is not returning any row OR not returning correct value.'];
                            echo json_encode($resultArray);
                            return;
                        }
                        $isInsert = true;
                        break;
                    
                    default:
                        return;
                        break;
                }
    
                if ($isInsert) {
                    
                    foreach($everyProductArray as $proQuanId) {
                        $insertIntoProductQuantityStmt->execute();
                    }

                    $insertCategoryGroupStmt->execute();
                    $conn->commit();
                    $resultArray = ['returnCode' => 200, 'message' => 'Successfully created new Category/Group'];
                    echo json_encode($resultArray);
                    return;
                }

            } catch (Error $e) {
                $conn->rollback();
                $resultArray = ['returnCode' => 500, 'message' => "Something went wrong with SQL. Error: $e. MySQL Error: " . mysqli_error($conn)];
            } catch (Exception $e) {

            }

        }
    }

?>