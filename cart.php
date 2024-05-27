<?php
    include 'server/connect.php';
    include 'server/user_session.php';
    include 'server/admin_user_type_validation.php';
    $current_page = 'cart';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('default_html_head.php'); ?>
    <link href="style/style-cart.css" rel="stylesheet">
    <link href="style/style-table.css" rel="stylesheet">
    <title>TVS | Cart</title>
</head>
<body>
    <!-- Pop Up For Delete -->
    <div class="modal modal-remove-from-cart fade" id="remove-from-cart" tabindex="-1" role="dialog" aria-labelledby="removeLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered justify-content-center" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    Remove item from cart?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-default-style" data-dismiss="modal" id="id-btn-remove" onclick="removeItem(event)">Remove</button>
                    <button type="button" class="btn btn-secondary btn-gray-style close-popup" data-dismiss="modal" id="id-btn-close-popup">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Pop Up  -->
    <div class="modal modal-display-removed-item fade" id="showDeleteItem" tabindex="-1" role="dialog" aria-labelledby="showDeleteItemLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered justify-content-center" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <p class="items-list">Removed Item (Out of Stock): </p>
                    
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-gray-style close-popup" data-dismiss="modal" id="id-btn-close-popup">Close</button>  
                </div>
            </div>
        </div>
    </div>
    <?php include ('header.php'); 
        
    ?>
    
    <main>
        <?php include ('top_section.php'); ?>
    
        <section class="sect-carts row">
            <div class="table-responsive-lg tbl-req big-table">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col"></th>
                            <th scope="col">Name</th>
                            <th scope="col">Quantity</th>
                            <th scope="col">Group</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="table-body">
                        <!-- <tr>
                            <td><img src="assets/img/merchandise/bag.png" alt=""></td>
                            <td>TVS | Bag</td>
                            <td>Stock left: 50</td>
                            <td>
                                <div class="quantity-control">
                                    <span class="add-sign">+</span>
                                    <span class="quantity-sign">3</span>
                                    <span class="minus-sign">-</span>
                                </div>
                            </td>
                        </tr> -->
                        <tr>
                            <td colspan="4">
                                <div class="spinner">
                                    <img src="assets/img/gif/loading.gif" alt="loading.gif">
                                </div>
                            </td>
                        </tr>
                </table>
            </div>
            
            <div class="btn-cart row justify-content-end">
                <button type="button" id='id-btn-confirm' class="btn btn-primary btn-default-style btn-confirm" onclick="confirmCart(event)" disabled>Confirm</button>
            </div>

        </section>
    </main>

    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    <script src="style/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/cart.js" type="text/javascript"></script>
    <?php
        include 'footer.php';
    ?>
</body>
</html>