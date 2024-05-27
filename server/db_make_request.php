<?php
    include 'connect.php';
    

    $totalDoc = $_POST['totalDoc'];

    if (isset($_POST['reqPurpose']) && isset($_POST['location'])) {
        $totalCart = $_SESSION['totalCart'];
        $location = $_POST['location'];

        // if ($totalCart == 0) {
        //     $returnArray = ['returnCode' => 404];
        //     echo json_encode($returnArray);
        //     return;
        // }

        $userId = $_SESSION['loggedin'];

        $newReqId = createUniqueId('RQ');

        // $cartItem = $_SESSION['cartItem'];
        // $cartId = $_SESSION['cartId'];
        // $requestLocation = $_SESSION['cart_location'];
        $requestPurpose = $_POST['reqPurpose'];

        try {
            $conn->begin_transaction();
            // CREATE NEW REQUEST
            $newRequestStmt = $conn->prepare('INSERT INTO request(request_id, request_date, request_status, request_purpose, modify_date, mer_loc_id, user_id)
                                                VALUES (?, ?, 0, ?, ?, ?, ?);');
            $newRequestStmt->bind_param('ssssss', $newReqId, $dateTime, $requestPurpose, $dateTime,  $location, $userId);
            

            // // CREATE NEW REQUEST ITEM
            // $sqlStmt = $conn->prepare('INSERT INTO requestdetail(request_id, product_id, request_quan, request_product_status)
            //             VALUES (?,?,?, 0);');
            
            // $sqlStmt->bind_param('sss', $newReqId, $code, $quantity);


            // // DELETE ITEM FROM CART
            // $deleteCartItemStmt = $conn->prepare('DELETE FROM cartitem
            //                                     WHERE cart_id = ?;');
            // $deleteCartItemStmt->bind_param('s', $cartId);                     
            
            
            // INSERT NOTIFICATION
            // $notiStmt = $conn->prepare('INSERT INTO notification(noti_id, noti_msg, noti_date, user_id)
            //                             VALUES (?, ?, ?, ?);');

            // $newNotiId = createUniqueId('NT');

            // $notiMsg = 'Request ID #' . $newReqId . ' has been sent for approval.';

            // $notiStmt->bind_param('ssss', $newNotiId, $notiMsg,  $dateTime, $userId);

            // START SQL COMMAND USING LOOP
         
            $newRequestStmt->execute();


            // for ($i = 0; $i < count($cartItem); $i++) {
            //     $code = $cartItem[$i][0];
            //     $quantity = $cartItem[$i][1];

            //     $sqlStmt->execute();
            // }

            // $deleteCartItemStmt->execute();
            // $notiStmt->execute();
            // $_SESSION['cartItem'] = '';
            // $_SESSION['totalCart'] = 0;

            $newRequestZipFolder = $_SESSION['requestFolder'] . '\\' . $newReqId;

            if (!file_exists($newRequestZipFolder)) {
                // mkdir($newRequestFolder, 0777);
            }
            // echo $newRequestZipFolder;


           
            $conn->commit();

            $zipArchive = new ZipArchive();
            $zipFile =  $newRequestZipFolder . '.zip';

            if ($zipArchive->open($zipFile, ZipArchive::CREATE) === TRUE) {

                for ($i = 0; $i < $totalDoc; $i++) {
                    uploadToZip($_FILES['uploadedFiles'. $i], $zipArchive);
                }
            } else {
                exit('Unable to open file');
            }
            $zipArchive->close();


            $name = '';
            $email = '';
            $password = '';


            $subject = "New Request Received: [$newReqId]";
            $body = "   <p>A new merchandise request has been submitted.</p>
                        <p>Request ID: $newReqId</p>
                        <p>Date Created: $dateTime</p>
                        <p>Requester: $name</p>
                        <p>Click this <a href='https://merchandise.smg.my/'>link</a> to proceed. Thank you!</p>
                        <br>
                        <p>Best Regards,</p>
                        <p>Merchandise System</p>";



            $sendTheMail = new Mail($name, $email, $password, $subject, $body, true, $recipients, null);
            $sendTheMail->sendTheEmail();
            $returnArray = ['returnCode' => 200];
            echo json_encode($returnArray);
            return;

            
        } catch (Exception $e) {
            $returnArray = ['returnCode' => 500, 'message' => "Error found: $e. MySQL Error: " . mysqli_error($conn)];
            echo json_encode($returnArray);
            return;

        }
    }


?>