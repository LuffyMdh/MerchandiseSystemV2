<?php
    include 'connect.php';
    
   
    if (isset($_POST['cartId'])) {
        $sessionCartId = $_SESSION['cartId'];
        $currentCardId = $_POST['cartId'];

        if ($sessionCartId == $currentCardId) {
            try {
                $deleteAllStmt =  $conn->prepare('DELETE FROM cartitem WHERE cart_id = ?');
                $deleteAllStmt->bind_param('s', $sessionCartId);
                $deleteAllStmt->execute();
                $_SESSION['cart_location'] = 0;
                echo 200;
                return;
            } catch (Error $e) {
                echo $e;
            }
        }
    }
?>