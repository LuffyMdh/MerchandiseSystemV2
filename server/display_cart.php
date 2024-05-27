<?php
    include 'connect.php';
    $userID = $_SESSION['loggedin'];
    $cartID = $_SESSION['cartId'];
    
    try {
        $stmt =  $conn->prepare("SELECT pro.product_id, pro.product_img, pro.product_name, pro.product_status, pg.p_group_name, ci.quantity, loc.mer_loc_id,  loc.mer_loc_name,
                                        (SELECT product_quan 
                                            FROM productquantity pq 
                                            WHERE pq.product_id = pro.product_id 
                                            AND pq.mer_loc_id = ci.mer_loc_id) AS product_quan
                                    FROM product pro
                                    INNER JOIN cartitem ci
                                    ON ci.product_id = pro.product_id
                                    JOIN cart ct
                                    ON ct.cart_id = ci.cart_id
                                    INNER JOIN merchandiselocation loc
                                    ON ci.mer_loc_id = loc.mer_loc_id
                                    INNER JOIN productgroupcategory pg
                                    ON pro.p_group_id = pg.p_group_id
                                    WHERE ct.user_id = ? 
                                    ORDER BY pro.p_group_id;");
        $stmt->bind_param("s", $userID);
        $stmt->execute();
        $result = $stmt->get_result();
        $rowCount = mysqli_num_rows($result);
        $cartItem = array();
        $cartQuantity = 0;
        $arrayOfCart = array();
        $itemRemoved  = array();
    } catch (Exception $e) {
        echo $e;
    }

    if (isset($_GET['request'])) { // Display item in request page (request.php)
        if ($rowCount > 0) {
            while ($cartDetail = $result->fetch_assoc()) {
                echo '
                <tr>
                    <td><img src="' . $cartDetail['product_img'] . '" width="50px" height="50px" alt="" /></td>
                    <td>' . $cartDetail['product_name'] . '</td>
                    <td>' . $cartDetail['p_group_name'] . '</td>
                    <td>x' . $cartDetail['quantity'] . '</td>
                </tr>
                ';
            }

        }

    } else if (isset($_POST['cart'])) { // Display item in cart page (cart.php)
        if ($rowCount > 0) {
        
            //echo json_encode(var$testing);
            while($cartDetail = $result->fetch_assoc()) {
                $itemInCart = true;

                // if ($cartDetail['product_quan'] == 0 || $cartDetail['product_status'] == 0) {
                    
                //     $dltItemStmt =  $conn->prepare('DELETE FROM cartitem 
                //                                 WHERE cart_id = ?
                //                                 AND product_id = ?;');
                //     $dltItemStmt->bind_param("ss", $cartID, $cartDetail['product_id']);
                //     $dltItemStmt->execute();
                //     array_push($itemRemoved, $cartDetail['product_name']);
                //     $cartDetail['quantity'] = 0;
                //     $itemInCart = false;
    
                // }

                if ($itemInCart) {
                    echo '
                    <tr>
                        <td><img src="' . $cartDetail['product_img'] . '" alt=""></td>
                        <td>' . $cartDetail['product_name'] . '</td>
                        <td>
                            <div class="quantity-control">
                                <span class="add-sign" data-location="' . $cartDetail['mer_loc_id'] . '" data-id="' . $cartDetail['product_id'] . '" onclick="addItemQuantity(event, 1)">+</span>
                                <span class="quantity-sign"><input type="text" value="' . $cartDetail['quantity'] . '" class="input-quan " inputmode="numeric" onfocusout="validateFocus(event,  \'' . $cartDetail['product_id'] . '\', \'' . $cartDetail['mer_loc_id'] . '\')" onKeyPress="return isNumber(event)"></span>
                                <span class="minus-sign" data-location="' . $cartDetail['mer_loc_id'] . '" data-id="' . $cartDetail['product_id'] . '" onclick="addItemQuantity(event, -1)">-</span>  
                                <div class="error-msg" style="margin-top:5px">
                                    <p style="margin: 0;">Invalid value!</p>
                                </div>
                            </div>
                        </td>
                        <td>' . $cartDetail['p_group_name'] . '</td>
                        <td class="trash-icon"><span title="Delete"><i class="bi bi-trash" data-id="' . $cartDetail['product_id'] . '" onclick="showDelete(\'' . $cartDetail['product_id'] . '\', \'' . $cartDetail['mer_loc_id'] .'\')"></i></span></td>
                    </tr>
                    ';
                }
                //<td class="trash-icon"><span title="Delete"><i class="bi bi-trash" data-id="' . $cartDetail['product_id'] . '" onclick="showDelete(\'' . $cartDetail['product_id'] . '\')"></i></span><span><i class="bi bi-layout-split"></i></span></td>
                
                // <div class="dropdown">
                //     <a class="hidden-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></a>
                //     <div class="dropdown-menu dot-dropdown" aria-labelledby="dropdownMenuButton">
                //         <a class="dropdown-item">Split</a>
                //         <a class="dropdown-item">Delete</a>
                //     </div>
                // </div>
                $tempArray = array($cartDetail['product_id'], $cartDetail['quantity'], $cartDetail['mer_loc_id']);
                array_push($cartItem, $tempArray);
                $cartQuantity += $cartDetail['quantity'];
            }

            if (!empty($itemRemoved)) {
                echo '<button type="button" id="popup-btn" onclick="displayRemovedItem([';
            
                foreach ($itemRemoved as $item) {
                    echo '\'' . $item . '\',';
                }
                
                echo '])" hidden></button>';
            }
    
    
        }
        
        echo '
        </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td>Total Quantity: </td>
                    <td class="cart-quantity">' . $cartQuantity . '</td>
                    <td style="width: 10px"></td>
                </tr>
            </tfoot>
        
        ';
    
        
        if (is_null($cartItem)) {
            $_SESSION['cartItem'] = '';
        } else {
            $_SESSION['cartItem'] = $cartItem;
            $_SESSION['totalCart'] = $cartQuantity;
        }
    }

?>