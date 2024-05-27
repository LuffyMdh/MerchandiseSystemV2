<?php
    include 'connect.php';

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['name']) && isset($_POST['group']) && isset($_POST['category']) && isset($_POST['desc']) && isset($_POST['quantity'])) {

            $name = $_POST['name'];
            $group = $_POST['group'];
            $cate = $_POST['category'];
            $desc = $_POST['desc'];
            $quantity = $_POST['quantity'];


            $uniqueId = createUniqueId('PR');


            if (isset($_FILES['img'])) {
                $imgFile = $_FILES['img'];
                $newFileLocation = moveImgLocation($imgFile);
            } else {
                $newFileLocation = 'assets/img/no_image.png';
            }

            try {
                $conn->begin_transaction();
                $addNewMercStmt = $conn->prepare('INSERT INTO product(product_id, product_name, product_desc, date, product_img, product_status, product_cate_id, p_group_id)
                                                    VALUES (?, ?, ?, ?, ?, 1, ?, ?);');
                $addNewMercStmt->bind_param('sssssss', $uniqueId, $name, $desc, $dateTime, $newFileLocation, $cate, $group);
                $addNewMercStmt->execute();

                $getLocationStmt = $conn->prepare('SELECT mer_loc_id FROM merchandiselocation');
                $getLocationStmt->execute();
                $getLocationResult = $getLocationStmt->get_result();
                
                $locationArray = array();
                if ($getLocationResult->num_rows > 0) {
                    while ($getLocation = $getLocationResult->fetch_assoc()) {
                        array_push($locationArray, $getLocation['mer_loc_id']);
                    } 
                }

                $insertIntoProductQuantityStmt = $conn->prepare('INSERT INTO productquantity(product_id, mer_loc_id, product_quan, product_location_status) 
                                                                    VALUES(?, ?, ?, ?)');
                $insertIntoProductQuantityStmt->bind_param('ssss', $uniqueId, $locationId, $quantityAdded, $locationStatus);

                foreach ($locationArray as $locationId) {
                    if ($locationId == 1) {
                        $quantityAdded = $quantity;
                        $locationStatus = 1;
                    } else {
                        $quantityAdded = 0;
                        $locationStatus = 0;
                    }

                    $insertIntoProductQuantityStmt->execute();
                }
               

                $conn->commit();

                $returnArray = ['returnCode' => 200, 'message' => "[$uniqueId][$name] - New merchandise added."];
                echo json_encode($returnArray);
                return;
            } catch (Error $e) {
                $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
                echo json_encode($returnArray);
                return;
            } catch (Exception $e) {
                $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
                echo json_encode($returnArray);
                return;
            }

        
        }
    }
?>