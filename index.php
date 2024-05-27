<?php
    include 'server/connect.php';
    include 'server/user_session.php';
    include 'server/db_get_prod_cate.php';
    include 'server/admin_user_type_validation.php';
    $current_page = 'index';

    $getGroupCategoryResult = getGroupCategory($conn);

    if ($_SESSION['totalCart'] == 0) {
        $_SESSION['cart_location'] = 0;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include ('default_html_head.php'); ?>
    <link href="style/style-table.css" rel="stylesheet">
    <title>TVS | Merchanise</title>
</head>
<body>
    <?php include ('header.php');  ?>
        <!-- Pop Up For Delete -->
        <div class="modal modal-remove-from-cart fade" id="remove-from-cart" tabindex="-1" role="dialog" aria-labelledby="removeLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered justify-content-center" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        You have items in your cart from another location. Would you like to remove existing item(s) and add this new one?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-default-style" data-dismiss="modal" id="id-btn-remove" data-cartId="<?php echo $_SESSION['cartId'] ?>" onclick="removeItemAll(event)">Yes</button>
                        <button type="button" class="btn btn-secondary btn-gray-style close-popup" data-bs-dismiss="modal">No</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- End Pop Up  -->
    <main>
        <?php include ('top_section.php'); ?>
        <div class="sect-product-category row">
            <section class="group-category col-2">
                <div class="container">
                    <ul>
                        <li><a class="group-list active-tab" data-groupid="all">All</a></li>
                        <?php 
                            if (mysqli_num_rows($getGroupCategoryResult) > 0) {
                                while ($group = $getGroupCategoryResult->fetch_assoc()) {
                                    echo '<li><a class="group-list" data-groupid="' . $group['p_group_id'] . '">' . $group['p_group_name'] . '</a></li>';
                                }
                            }
                        ?>
                    </ul>
                </div>
            </section>
            <section class="sect-product col">
                <div class="container another-container">
                    <div class="products col-lg">
                        <div class="product-showcase row" id="display-result">

                        </div>
                    </div>
                </div>

                <div class="pagination">
                    <span class="previous"><</span>
                    <span class="current">1</span>
                    <span class="next">></span>
                </div>
            </section>
        </div>

    </main>
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    <script src="style/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/index.js" type="text/javascript"></script>
    <?php
        include 'footer.php';
    ?>
</body>
</html>