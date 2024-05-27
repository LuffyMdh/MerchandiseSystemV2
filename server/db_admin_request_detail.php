<?php 
    include 'connect.php';

    if (isset($_GET['requestId'])) {
        $requestId = $_GET['requestId'];

        try {
            $getRequestDataStmt = $conn->prepare('SELECT request_status, mer_loc_id 
                                                FROM request
                                                WHERE request_id = ?;');
            $getRequestDataStmt->bind_param('s', $requestId);


            $getRequestDataStmt->execute();
            $getRequsetDataResult = $getRequestDataStmt->get_result();

            $getRowCount = mysqli_num_rows($getRequsetDataResult);

            if ($getRowCount > 0) {
                $array = array();

                $getRequestData = $getRequsetDataResult->fetch_assoc();
                $requestStatus = $getRequestData['request_status'];
                $requestLocation = $getRequestData['mer_loc_id'];
                $returnArray = ['returnCode' => 200, 'requestStatus' => $requestStatus, 'requestLocation' => $requestLocation];
                echo json_encode($returnArray);
                return;
            } else {
                $returnArray = ['returnCode' => 404, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
                echo json_encode($returnArray);
                return;
            }

        } catch (Exception $e) {
            echo 'Error found: ' . $e;
        }
    }

?>