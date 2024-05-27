<?php 

    if (!isset($_SESSION['loggedin'])) {
        $absolutePath = realpath(__DIR__);

        $pathArray =  explode('\\', $absolutePath);
        $currentFile = $pathArray[count($pathArray) - 1];
        
        if ($currentFile == 'server') {
            header('Location: ./login_page.php');
        } else {
            header('Location: login_php');
        }
        
    } else {
        $user_type = $_SESSION['user_type'];
        
        $userID = $_SESSION['loggedin'];

        if (isset($_SESSION['adminRequestDetail'])) {
            unset($_SESSION['adminRequestDetail']);
        }
    }

?>
