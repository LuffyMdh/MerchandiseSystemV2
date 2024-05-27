<?php
    include 'connect.php';

    if (isset($_SESSION['cart_location']) && isset($_POST['code']) && isset($_POST['location'])) {
        $cartLocation = $_SESSION['cart_location'];
        $locationId = $_POST['location'];
        $productCode = $_POST['code'];
        $cartId = $_SESSION['cartId'];

        if ($cartLocation == 0 || $cartLocation == $locationId) {
            try {
                // SELECT in WHERE clause to validate if the product is not 0
                $addStmt = $conn->prepare("INSERT INTO cartitem (cart_id, quantity, product_id, mer_loc_id)
                                                SELECT ?, 1, ?, ?
                                                WHERE (SELECT product_quan FROM productquantity WHERE product_id = ? AND mer_loc_id = ? AND product_quan != 0);");
    
                $addStmt->bind_param("sssss", $cartId, $productCode, $locationId, $productCode, $locationId);
                $addStmt->execute();
                $totalCart = $_SESSION['totalCart'];
                $totalCart++;
                $_SESSION['totalCart'] = $totalCart;
                
                if (isset($_SESSION['cart_location'])) {
                    $_SESSION['cart_location'] = $locationId;
                }
                
                echo 200;
                return;
            } catch (Error $e) {
                echo $e;
            }
        } 

        if ($cartLocation != $locationId) {
           echo 400;
           return;
        }
    }
    
?>