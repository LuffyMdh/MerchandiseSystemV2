<?php
    include 'connect.php';
    $userId = $_SESSION['loggedin'];

    if (isset($_GET['data'])) {
        $notiArray = array();
        try {
    
            $getNotiStmt = $conn->prepare("SELECT noti_msg, noti_isRead
                                            FROM notification
                                            WHERE user_id = ?  
                                            ORDER BY noti_date DESC;");
    
            $getNotiStmt->bind_param('s', $userId);
            $getNotiStmt->execute();
    
            $notiResult = $getNotiStmt->get_result();
            $notiRow = mysqli_num_rows($notiResult); 
            $counter = 0;
    
            if ($notiRow > 0) {
                while ($notiDetail = $notiResult->fetch_assoc()) {
                    $counter++;
                    echo '
                    <li '; echo ($notiDetail['noti_isRead'] == 0) ? 'style="border-left: 3px solid var(--main-color);" data-noti="1"' : ''; echo '>
                        <div>
                            <p>' . $notiDetail['noti_msg'] . '</p>
                        </div>
                    </li>
                    ';
                
                    if ($notiRow != 1 && $counter != $notiRow) {
                        echo '<li><hr class="dropdown-divider"></li>';
                    }
                }
                $_SESSION['new_noti'] = true;
            } else {
                echo '
                <li>
                    <div>
                        <p>No Notification</p>
                    </div>
                </li>
                ';
            }
    
    
    
        } catch (Exception $e) {
            echo $e;
        }
    } else if (isset($_POST['data'])) {
        try {
            $updateNotiReadStmt = $conn->prepare('UPDATE notification
                                                    SET noti_isRead = 1
                                                    WHERE user_id = ?;');

            $updateNotiReadStmt->bind_param('s', $userId);
            $updateNotiReadStmt->execute();

        } catch (Exception $e) {
            echo $e;
        }
    }

?>