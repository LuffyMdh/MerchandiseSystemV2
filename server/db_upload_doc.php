<?php
    include 'connect.php';

    if (isset($_POST['code'])) {
        $userId = $_SESSION['loggedin'];
        $requestId = $_POST['code'];


        $folderLocation = getFolderLocation() . '\assets\attachment\request\user\\' . $userId . '\\' . $requestId . '\\';

        $zipArchive = new ZipArchive();
        $zipFile =  $folderLocation . $requestId . '.zip';

        if ($zipArchive->open($zipFile, ZipArchive::CREATE) === TRUE) {
            $addFile = $folderLocation . 'DF66273554a49e7.pdf';
            $zipArchive->addFile($addFile, basename($addFile));
        } else {
            exit('Unable to open file');
        }

        $zipArchive->close();
        echo 'Zip file is created';

    }


?>