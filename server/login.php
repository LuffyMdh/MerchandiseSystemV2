<?php
    $loginPage = true;
    include 'connect.php';
    
    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['userEmail']) && isset($_POST['userPass'])) {
            try {
                $usrEmail = $_POST['userEmail'];
                $usrEmail = filter_var($usrEmail, FILTER_SANITIZE_STRING);
                $usrPassword = md5($_POST['userPass']);
                $usrPassword = filter_var($usrPassword, FILTER_SANITIZE_STRING);
                $stmt =  $conn->prepare("SELECT empid, name, email, unit, designation FROM $smgEmpTable WHERE email = ? AND password=?");
                $stmt->bind_param("ss", $usrEmail, $usrPassword);
                $stmt->execute();
                $result = $stmt->get_result();
                $rowCount = mysqli_num_rows($result);  
    
                if ($rowCount == 1) {
                    $userDetail = $result->fetch_assoc();
                    $unit = $userDetail['unit'];
                    $designation = $userDetail['designation'];
    
                    if ($designation == 'Head of Marketing and Communications') {
                        $_SESSION['loggedin'] = $userDetail['email'];
                        $_SESSION['loggedin_name'] = $userDetail['name'];
                        $_SESSION['user_type'] = 'admin';
                        $returnArray = ['returnCode' => 201];
                        echo json_encode($returnArray);
                        return;
                    }

                    if ($unit != 'Public Relations and Promotion') {
                        
                        $_SESSION['loggedin'] = $userDetail['email'];
                        $_SESSION['loggedin_name'] = $userDetail['name'];
                        $userRequestFolder = getFolderLocation() . '\\assets\\attachment\\request\\user\\' . $_SESSION['loggedin'];
                        
                        $cartStmt =  $conn->prepare("SELECT cart.cart_id, SUM(cartitem.quantity) as totalQuantity, cartitem.mer_loc_id as locationId
                                                        FROM cart
                                                        LEFT JOIN cartitem 
                                                        ON cart.cart_id = cartitem.cart_id
                                                        WHERE cart.user_id = ?");
            
                        $cartStmt->bind_param("s", $_SESSION['loggedin']);
                        $cartStmt->execute();
                        $cartResult = $cartStmt->get_result();
                        $totalQuantity = $cartResult->fetch_assoc();
                       
                        if (empty($totalQuantity['cart_id'])) { // For first time user (Create Cart ID & Folder for)
                            
                            $uniqueID = uniqid();
                            $uniqueID = substr(strtoupper($uniqueID), 4, 6);
                            $uniqueID = 'CT' . $uniqueID;
    
                            $makeCartStmt = $conn->prepare('INSERT INTO cart(cart_id, user_id) 
                                                            VALUES (?, ?);') or die(mysql_error());

                            $makeCartStmt->bind_param('ss', $uniqueID, $_SESSION['loggedin']);
                            $makeCartStmt->execute();
                            $_SESSION['totalCart'] = 0;
                            $_SESSION['cartId'] = $uniqueID;

                            $makeCartStmt->execute();
                            
                           
                            
                            if (!file_exists($userRequestFolder)) {
                                mkdir($userRequestFolder, 0700);
                                writeToLog("\n" . funcGetDate('MY') . ": Successfully created new user folder location. [" . $_SESSION['loggedin'] . "] \n");
                            }
                            writeToLog("\n" . funcGetDate('MY') . ": Successfully created new user cart. [" . $_SESSION['loggedin'] . "] \n");
                            $_SESSION['requestFolder'] = $userRequestFolder . '\\';

                        } else {
                            if (is_null($totalQuantity['totalQuantity'])) {
                                $_SESSION['totalCart'] = 0;
                                $_SESSION['cart_location'] = 0;
                            } else {
                                $_SESSION['totalCart'] = $totalQuantity['totalQuantity'];
                                $_SESSION['cart_location'] = $totalQuantity['locationId'];
                            }
                            
                            $_SESSION['cartId'] =  $totalQuantity['cart_id'];
                            $_SESSION['requestFolder'] = $userRequestFolder;
                        }
                        
                        $_SESSION['user_type'] = 'normal';
                        $tempArray = ['returnCode' => 200];
                        echo json_encode($tempArray);
                        return;
                    } else {
                        $_SESSION['loggedin'] = $userDetail['email'];
                        $_SESSION['loggedin_name'] = $userDetail['name'];
                        $_SESSION['user_type'] = 'admin';
                        $returnArray = ['returnCode' => 201];
                        echo json_encode($returnArray);
                    }
    
                   
                } else {
                    $returnArray = ['returnCode' => 404];
                    echo json_encode($returnArray);
                    return;
                }
            } catch (Throwable $e) {
                $returnArray = ['returnCode' => 400, 'message' => 'Is this error? : '. $e];
                writeToLog("\n" . funcGetDate('MY') . ": " . $returnArray['message'] . "\n");
                echo json_encode($returnArray);
                return;
            }
        }
    }


?>