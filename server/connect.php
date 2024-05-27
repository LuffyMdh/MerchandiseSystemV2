<?php
    session_start();
    include_once 'db.php';
    include_once 'mail_class.php';

    if (!isset($_SESSION['loggedin']) && !isset($loginPage)) {

        $absolutePath = realpath(__DIR__);

        $pathArray =  explode('\\', $absolutePath);
        $currentFile = $pathArray[count($pathArray) - 1];
        if ($currentFile == 'server') {
            header('Location: ../login_page.php');
        }

    } else {
        $folderPath = getcwd();
        $realPath = realpath(__DIR__ . '/..');
        $conn = openConMerchandise();
        // $connSmg = openConSmg();
    
        $conn->select_db('merchandise');
    
        $smgEmpTable = 'mainportal.tblemployee';   
    }

    $sessionTimeOut = 5;


    // if (isset($_SESSION['ACTIVITY_ACTIVE'])) {
    //     $lastActivity = $_SESSION['ACTIVITY_ACTIVE'];
    //     $currentTime = time();
    //     $timeSinceActive = $currentTime - $lastActivity;

    //     if ($timeSinceActive > $sessionTimeOut) {
    //         session_unset();
    //         session_destroy();

    //         if (!isset($_SESSION['loggedin'])) {
    //             header('Location: ../login_page.php');
    //         }

           
    //     } else {
    //         $_SESSION['ACTIVITY_ACTIVE'] = $currentTime;
    //     }
    // } else {
    //     $_SESSION['ACTIVITY_ACTIVE'] = time();
    // }



    // Random Function
    function changeDate($date) {
        $dateFormat = date_create($date);
        $dateFormat = date_format($dateFormat, 'd/m/Y');
        return $dateFormat;
    }

    function getStatus($status) {
        
        switch ($status) {
            case '0':
                return 'Pending';
                break;
            case '1':
                return 'Accepted';
                break;
            case '-1':
                return 'Rejected';
                break;
            default:
                return 'Not found?';
                break;
        }
    }

    function createUniqueId($prefix) {
        $newReqId = uniqid();
        $newReqId = substr(strtoupper($newReqId), -6);
        $newReqId = $prefix . $newReqId;
        return $newReqId;
    }

    function getFolderLocation() {
        $path = realpath(__DIR__ . '/..');
        return $path;
    }

    function moveImgLocation($imgFile) {
        $realPath = realpath(__DIR__ . '/..');
        $img = $imgFile;
        $tempImg = $img['tmp_name'];
        $ext = pathinfo($img['name'], PATHINFO_EXTENSION);
        $imgName = uniqid('PR');
        $newImgPath = 'assets/img/merchandise/' . $imgName . '.' . $ext;
        $targetPath = $realPath . '/' . $newImgPath;
        move_uploaded_file($tempImg, $targetPath);
        return $newImgPath;
    }

    function uploadToZip($file, $zipArchive) {
        $tempFile = $file['tmp_name'];
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $fileId = uniqid('DF');
        $fileId = $fileId . '.' . $ext;
        $zipArchive->addFile($tempFile, $fileId);
    }

    function moveDocLocation($docFile, $userId, $requestId) {
        $path = getFolderLocation();

        $tempDoc = $docFile['tmp_name'];
        $ext = pathinfo($docFile['name'], PATHINFO_EXTENSION);
        $fileName = uniqid('DF');
        $newFilePath = $_SESSION['requestFolder'] . '\\' . $requestId . '\\' . $fileName . '.' . $ext;
        move_uploaded_file($tempDoc, $newFilePath);
        
    }

    function getTotalQMark($data) {
        return rtrim(str_repeat('?,', count($data)), ',');
        
    }

    function getTotalSMark($data) {
        return str_repeat('s', count($data));
    }

    function getMerchLocation($conn) {
        try {
            $getLocationStmt = $conn->prepare('SELECT mer_loc_id, mer_loc_name
                                FROM merchandiselocation;');
            $getLocationStmt->execute();
            return $getLocationStmt->get_result();
        } catch (Exception $e) {
            echo $e;
        }
    }

    function districtIsoCodes($district) {
        $arrayDistrict = array( 'Kuching' => 'KCH',
                                'Kuala Lumpur' => 'KL');

        foreach($arrayDistrict as $districtName => $districtAbb) {
            if ($districtName == $district) {
                return $districtAbb;
            }
        }
        
    }

    function getLocation($conn) {
        try {
            $getAllLocationStmt = $conn->prepare('SELECT * FROM merchandiselocation');
            $getAllLocationStmt->execute();
            return $getAllLocationStmt->get_result();
        } catch (Exception $e) {
            echo $e;
            echo mysqli_error($conn);
        } catch (Error $e) {
            echo $e;
            echo mysqli_error($conn);
        }
    }

    function getGroupCategory($conn) {
        try {
            $getAllGrpCateStmt = $conn->prepare('SELECT * FROM productgroupcategory');
            $getAllGrpCateStmt->execute();
            return $getAllGrpCateStmt->get_result();
        } catch (Exception $e) {
            echo $e;
            echo mysqli_error($conn);
        } catch (Error $e) {
            echo $e;
            echo mysqli_error($conn);
        }
    }

    function getRequesterLocation($conn) {
        try {
            $getRequesterLocationStmt = $conn->prepare('SELECT mer_loc_name FROM merchandiselocation WHERE mer_loc_id = ?');
            $getRequesterLocationStmt->bind_param('s', $_SESSION['cart_location']);
            $getRequesterLocationStmt->execute();
            return $getRequesterLocationStmt->get_result();
        } catch (Exception $e) {
            echo $e;
            echo mysqli_error($conn);
        } catch (Error $e) {
            echo $e;
            echo mysqli_error($conn);
        }
    }

    function adminRequestValidate($conn, $requestId, $productId) {
        try {
            $validateStmt = $conn->prepare('SELECT rd.request_quan, pro.product_name, rc.rd_comment
                                                FROM requestdetail rd 
                                                INNER JOIN product pro
                                                ON pro.product_id = rd.product_id
                                                LEFT JOIN requestdetailcomment rc
                                                ON rc.product_id = rd.product_id AND rc.request_id = rd.request_id
                                                WHERE rd.request_id = ? AND rd.product_id = ? AND rd.request_product_status = 0');
            $validateStmt->bind_param('ss', $requestId, $productId);
            $validateStmt->execute();
            $validateResult = $validateStmt->get_result();

            return $validateResult;

        } catch (Error $e) {
            echo $e;
            echo mysqli_error($conn);
        } catch (Exception $e) {
            echo $e;
            echo mysqli_error($conn);
        }
    }

    function getProductQuantity($conn, $productId) {
        try {
            $getProductQuantityStmt = $conn->prepare('SELECT mer_loc_id, product_quan, product_location_status FROM productquantity WHERE product_id = ?');
            $getProductQuantityStmt->bind_param('s', $productId);
            $getProductQuantityStmt->execute();
            $getProductQuantityResult = $getProductQuantityStmt->get_result();
            return $getProductQuantityResult;

        } catch (Error $e) {
            echo $e;
            echo mysqli_error($conn);
        } catch (Exception $e) {
            echo $e;
            echo mysqli_error($conn);
        }
    }

    function splitDateTime($dateTime, $index) {
        $splitString = explode(' ', $dateTime);
        
        if ($index == 0) {
            return $splitString[0];
        } else {
            return $splitString[1];
        }
    }

    function funcGetDate($format) {
        date_default_timezone_set('Asia/Kuching');
        if ($format == 'MY') {
            $date = date('d-m-Y');
            $time = date('H:i:s');
            return $date . ' ' . $time;
        } else if ($format == 'DF') {
            $date = date('Y-m-d');
            $time = date('H:i:s');
            return  $date . ' ' . $time;
        }

    }

    function writeToLog($message) {
        $ROOT_DIR = realpath(__DIR__ . '/..');
        $fileLocation = $ROOT_DIR . '/assets/log/log.txt';
        $fileOpen = fopen($fileLocation, 'a') or die('File cannot be opened');
        fwrite($fileOpen, $message);
        fclose($fileOpen);
    }

    date_default_timezone_set('Asia/Kuching');
    $date = date('Y-m-d');
    $time = date('H:i:s');
    $dateTime = $date . ' ' . $time;

    if (!isset($_SESSION['loggedin'])) {
        header('../login_page.php');
    }
?>