<?php
    include 'connect.php';
    if (isset($_POST['productId']) && isset($_POST['requestId'])) {
        $productId = $_POST['productId'];
        $requestId = $_POST['requestId'];
        $userType = $_SESSION['user_type'];

        if ($userType == 'normal') {
            $userId = $_SESSION['loggedin'];
            try {
                $getHistoryUserStmt = $conn->prepare('SELECT rd_comment, rd_comment_date
                                                        FROM requestdetailcomment rc
                                                        INNER JOIN request rq
                                                        ON rq.request_id = rc.request_id
                                                        WHERE rc.request_id = ? AND rc.product_id = ? AND rq.user_id = ?;');
                $getHistoryUserStmt->bind_param('sss', $requestId, $productId, $userId);
                $getHistoryUserStmt->execute();
                $getHistoryUserResult = $getHistoryUserStmt->get_result();

                if ($getHistoryUserResult->num_rows > 0) {
                    $historyArray = array();
                    while ($getHistoryUser = $getHistoryUserResult->fetch_assoc()) {
                        array_push($historyArray, changeDate($getHistoryUser['rd_comment_date']). " - " . $getHistoryUser['rd_comment']);
                    }

                    $tempArray = ['returnCode' => 200, 'historyArray' => $historyArray];
                    echo json_encode($tempArray);
                    return;
                } else {
                    $tempArray = ['returnCode' => 200, 'historyArray' => [ changeDate($dateTime) . " - No history found!"]];
                    echo json_encode($tempArray);
                    return;
                }
            } catch (Exception $e) {

            } catch (Error $e) {

            }
            
        } else if($userType == 'admin'){
            try {
                $getHistoryStmt = $conn->prepare('SELECT rd_comment, rd_comment_date FROM requestdetailcomment WHERE request_id = ? AND product_id = ? ORDER BY rd_comment_date DESC');
                $getHistoryStmt->bind_param('ss', $requestId, $productId);
                $getHistoryStmt->execute();
                $getHistoryResult = $getHistoryStmt->get_result();
    
                if (mysqli_num_rows($getHistoryResult) > 0) {
                    while ($getHistory = $getHistoryResult->fetch_assoc()) {
                        echo '
                            <p>' . changeDate($getHistory['rd_comment_date']) . ' - ' . $getHistory['rd_comment'] . '</p>
                        ';
                    }
                }
    
            } catch (Error $e) {
                echo $e;
                echo mysqli_error($conn);
            } catch (Exception $e) {
                echo $e;
                echo mysqli_error($conn);
            }
        }
    }    
?>