<?php
    include 'connect.php';

    if (isset($_POST['code'])) {
        $cartItem = $_SESSION['cartItem'];
        $productCode = $_POST['code'];
        $operator = $_POST['operator'];
        $enterQuan = $_POST['enterQuan'];
        $totalCart = $_SESSION['totalCart']; 
        $cartID = $_SESSION['cartId'];
        $locationId = $_POST['locationId'];
        $itemIndex = 0;
        
        $itemFoundInCart = false;
        for ($i = 0; $i < count($cartItem); $i++) {
            if ($productCode == $cartItem[$i][0] && $locationId == $cartItem[$i][2]) {
                $itemFoundInCart = true;
                $itemIndex = $i;
            }
        }

        if (!$itemFoundInCart) {
            echo 404;
            return;
        }

        $currentQuantity = $cartItem[$itemIndex][1];
        $updateQuantity = false;

        if ($operator == 1) {
            $currentQuantity++;
            $totalCart++;
            $updateQuantity = true;
        } else if ($operator == -1) {
            $currentQuantity--;
            $totalCart--;

            if ($currentQuantity == 0) {
                echo 300;
                return;
            }
            $updateQuantity = true;
        } else if ($operator == 2) {
            if (is_numeric($enterQuan)) {
                if ($currentQuantity != $enterQuan) {
                    $totalCart = $totalCart - $currentQuantity;
                    $currentQuantity = $enterQuan;
                    $totalCart = $totalCart + $currentQuantity;
                    $updateQuantity = true;
                }
            } else {
                echo 400;
                return;
            }
            
        }
        
        if ($updateQuantity) {
            try {
                $updateQuantityStmt = $conn->prepare('UPDATE cartitem
                                                        SET quantity = ?
                                                        WHERE product_id = ?
                                                        AND cart_id = ?
                                                        AND mer_loc_id = ?');
                $updateQuantityStmt->bind_param('ssss', $currentQuantity, $productCode, $cartID, $locationId);
                $updateQuantityStmt->execute();
                $cartItem[$itemIndex][1] = $currentQuantity;
                $_SESSION['cartItem'] = $cartItem;
                $_SESSION['totalCart'] = $totalCart;

                echo 200;
                return;
            } catch (Throwable $e) {
                echo $e;
                echo mysqli_error($conn);
            } catch (Exception $e) {
                echo $e;
    
            } catch (Error $e) {
                echo $e;
            }
        }

   }

   

?>