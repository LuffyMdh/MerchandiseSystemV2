<?php
    include 'connect.php';
    
    $userType = $_SESSION['user_type'];
    

    if (isset($_POST['code']) && $userType == 'normal') {
        $userId = $_SESSION['loggedin'];
        $requestId = $_POST['code'];
        try {
            $getUserRequestStmt = $conn->prepare('SELECT user_id FROM request WHERE user_id = ? AND request_id = ?');
            $getUserRequestStmt->bind_param('ss', $userId, $requestId);
            $getUserRequestStmt->execute();
            $getUserRequestResult = $getUserRequestStmt->get_result();
            $arrayResult = array();

            if (mysqli_num_rows($getUserRequestResult) > 0) {
                $folderLocation = getFolderLocation();
                $domainName = $_SERVER['HTTP_HOST']; // Note on this
                $folderLocation = $domainName . '\\assets\\attachment\\request\\user\\' . $userId .  '\\' . $requestId . '.zip';

      

                $arrayResult[200] = $folderLocation;
                echo json_encode($arrayResult);
            } else {
                echo 404;
            }
        
        } catch (Exception $e) {
            echo json_encode(mysqli_error($conn));
            echo json_encode($e);
        } catch (Error $e) {
            echo json_encode(mysqli_error($conn));
            echo json_encode($e);
        }
    } else if (isset($_POST['code']) && isset($_POST['userId']) && $userType == 'admin') {
       $requestId = $_POST['code'];
       $userId = $_POST['userId'];
        try {
            $folderLocation = getFolderLocation();
            $domainName = $_SERVER['HTTP_HOST']; // Note on this
            $folderLocation = $domainName . '\\assets\\attachment\\request\\user\\' . $userId .  '\\' . $requestId . '.zip';

  

            $arrayResult[200] = $folderLocation;
            echo json_encode($arrayResult);

        } catch (Exception $e) {
            echo json_encode(mysqli_error($conn));
            echo json_encode($e);
        } catch (Error $e) {
            echo json_encode(mysqli_error($conn));
            echo json_encode($e);
        }
    }
?>