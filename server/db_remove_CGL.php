<?php
    include 'connect.php';

    $returnArray = array();
    if (isset($_POST['removeCode']) && isset($_POST['removeType'])) {
        $removeCode = $_POST['removeCode'];
        $removeType = $_POST['removeType'];
        $admin = $_SESSION['loggedin'];

        try {
            switch ($removeType) {
                case '1':
                    $deleteCategoryStmt = $conn->prepare('DELETE FROM productcategory WHERE product_cate_id = ?');
                    $deleteCategoryStmt->bind_param('s', $removeCode);
                    $deleteCategoryStmt->execute();
                    
                    if ($deleteCategoryStmt->errno == 1451) {
                        $returnArray = ['returnCode' => 405, 'message' => 'Uh-oh! Can\'t remove this category just yet. Some merchandise items are still linked to it. Please detach all items before retrying.'];
                        // writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . ". This transaction is done by [$admin].\n");
                        echo json_encode($returnArray);
                        return;
                    } else {
                        $returnArray = ['returnCode' => 200, 'message' => 'Category successfully removed.'];
                        echo json_encode($returnArray);
                        return;
                    }
                    break;

                case '2':
                    $deleteCategoryStmt = $conn->prepare('DELETE FROM productgroupcategory WHERE p_group_id = ?');
                    $deleteCategoryStmt->bind_param('s', $removeCode);
                    $deleteCategoryStmt->execute();
                    
                    if ($deleteCategoryStmt->errno == 1451) {
                        $returnArray = ['returnCode' => 405, 'message' => 'Oops! Looks like we can\'t remove this group just yet. There are still merchandise items linked to it. Please ensure all items are unassociated before trying again.'];
                        // writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . ". This transaction is done by [$admin].\n");
                        echo json_encode($returnArray);
                        return;
                    } else {
                        $returnArray = ['returnCode' => 200, 'message' => 'Group successfully removed.'];
                        echo json_encode($returnArray);
                        return;
                    }
                    break;

                case '3':

                    $returnArray = ['returnCode' => 200, 'message' => 'Remove location?'];
                    echo json_encode($returnArray);
                    return;
                    break;

                default:
                    return;
                    break;
            }

        } catch (Exception $e) {
            $returnArray = ['returnCode' => 500, 'message' => "Something went wrong with the SQL. Error: $e. MySQL Error: " . mysqli_error($conn)];
            writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " \n");
            echo json_encode($returnArray);
            return;
        } catch (Error $e) {
            $returnArray = ['returnCode' => 500, 'message' => "Something went wrong with the SQL. Error: $e. MySQL Error: " . mysqli_error($conn)];
            echo json_encode($returnArray);
            writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message']  . " \n");
            return;
        }
        
    } else {
        return;
    }
?>