<?php
    function openConMerchandise() {
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = '';
        $db = 'merchandise';

        $conn = new mysqli($dbhost, $dbuser, $dbpass) or die('Connection failed: ' . $conn->error);
 
        return $conn;
    }

    function closeConMerchandise($conn) {
        $conn -> close();
    }
?>