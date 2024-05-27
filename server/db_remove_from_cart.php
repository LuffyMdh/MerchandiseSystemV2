<?php
    include 'connect.php';
    if (isset($_POST['code']) && isset($_POST['locationId'])) {

        $cartId = $_SESSION['cartId'];
        $totalCart = $_SESSION['totalCart'];
        $productCode = $_POST['code'];
        $cartItem = $_SESSION['cartItem'];
        $locationId = $_POST['locationId'];
        $itemIndex = 0;

        $itemFoundInCart = false;

        for ($i = 0; $i < count($cartItem); $i++) {
            if (($cartItem[$i][0] == $productCode) && ($cartItem[$i][2] == $locationId)) {
                $itemFoundInCart = true;
                $itemIndex = $i;
                $totalCart = $totalCart = $cartItem[$i][1];
            }
        }

        if (!$itemFoundInCart) {
            echo 404;
            return;
        }
        
        try {
            $dltStmt = $conn->prepare('DELETE FROM cartitem
                                        WHERE product_id = ?
                                        AND cart_id = ?
                                        AND mer_loc_id = ?;');
            $dltStmt->bind_param("sss", $productCode, $cartId, $locationId);
            $dltStmt->execute();
            $_SESSION['totalCart'] = $totalCart;

            echo 200;
        } catch (Error $e) {
            echo $e;
            echo mysqli_error($conn);
        }

    }
?>