<?php 
    include 'connect.php';

    if (isset($_POST['code'])) {

        $code = $_POST['code'];
        $name = $_POST['name'];
        $group = $_POST['group'];
        $cate = $_POST['category'];
        $desc = $_POST['desc'];
    
        $statusArray = array();

        try {
            $conn->begin_transaction();

            if (isset($_FILES['img'])) {
                $currentImg = $_POST['currentImg'];
                $newImgPath = moveImgLocation($_FILES['img']);
                $pathCurrentImg = $realPath . '\\' . $currentImg;

                $imageName = explode('/', $currentImg);
                $imageName = $imageName[count($imageName) - 1];

                if ($imageName != 'no_image.png') {
                    unlink($pathCurrentImg);
                }

                
                
                $updateProductDetailStmt = $conn->prepare('UPDATE product
                                                            SET product_name = ?,
                                                                product_desc = ?,
                                                                product_img = ?,
                                                                product_cate_id = ?,
                                                                p_group_id = ?
                                                            WHERE product_id = ?;');
                $updateProductDetailStmt->bind_param('ssssss', $name, $desc,  $newImgPath, $cate, $group, $code);
                
                $statusArray['message'] = 'Product ID: ' . $code . ' is updated with photo.';
                $statusArray['statusCode'] = 200;

            } else {
                $updateProductDetailStmt = $conn->prepare('UPDATE product
                                                            SET product_name = ?,
                                                                product_desc = ?,
                                                                product_cate_id = ?,
                                                                p_group_id = ?
                                                            WHERE product_id = ?;');
                $updateProductDetailStmt->bind_param('sssss', $name, $desc, $cate, $group, $code);
                $statusArray['message'] = 'Product ID: ' . $code . ' is updated.';
                $statusArray['statusCode'] = 200;
            }

            if (isset($_POST['location']) && isset($_POST['quantity'])) {
                $location = $_POST['location'];
                $quantity = $_POST['quantity'];

            

                $updateQuantityStmt = $conn->prepare('UPDATE productquantity SET product_quan = ? WHERE product_id = ? AND mer_loc_id = ? AND product_location_status = 1');
                $updateQuantityStmt->bind_param('sss', $quantity, $code, $location);
                $updateQuantityStmt->execute();
                $statusArray['message'] = $statusArray['message'] . ' Updated with new quantity.';
            }

            $updateProductDetailStmt->execute();
            $conn->commit();

            $tempShit = $_POST['currentImg'];

            echo json_encode($statusArray);
        } catch (Error $e) {
            $statusArray['message'] = 'Error found: ' . $e . '. MySQLI Error: ' . mysqli_error($conn);
            $statusArray['statusCode'] = 400;
            echo json_encode($statusArray);
        } catch (Exception $e) {
            $statusArray['message'] = 'Error found: ' . $e . '. MySQLI Error: ' . mysqli_error($conn);
            $statusArray['statusCode'] = 400;
            echo json_encode($statusArray);
        } catch (Throwable $e) {
            $statusArray['message'] = 'Error found: ' . $e . '. MySQLI Error: ' . mysqli_error($conn);
            $statusArray['statusCode'] = 400;
            echo json_encode($statusArray);
        }
    } else {
        header('Location: ../admin_merchandise.php');
    }
?>