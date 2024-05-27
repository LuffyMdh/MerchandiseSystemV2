<?php
    include 'server/connect.php';
    include 'server/user_session.php';
    $current_page = 'admin_merchandise';


    if (isset($_GET['productCode']) || (!isset($_GET['categoryCode']))) {
        header('Location: admin_merchandise.php?categoryCode=all');
    }

    if (isset($_GET['categoryCode'])) {
        if ($_GET['categoryCode'] != 'all') {
            header('Location: admin_merchandise.php?categoryCode=all');
        }
    }

    try {
        $getCategoryStmt = $conn->prepare("SELECT product_cate_id, cate_name
                                            FROM productcategory;");
            
        $getCategoryStmt->execute();
        $getCategoryResult = $getCategoryStmt->get_result();
        if (mysqli_num_rows($getCategoryResult) > 0) {
            $categoryArray = array();
            while ($getCategory = $getCategoryResult->fetch_assoc()) {
                $categoryArray[$getCategory['product_cate_id']] = $getCategory['cate_name'];
            }
        }

        $getMerchandiseLocationStmt = $conn->prepare('SELECT mer_loc_id, mer_loc_name FROM merchandiselocation');
        $getMerchandiseLocationStmt->execute();
        $getMerchandiseLocationResult = $getMerchandiseLocationStmt->get_result();

        if (mysqli_num_rows($getMerchandiseLocationResult) > 0) {
            $locationArray = array();
            while ($getMerchandiseLocation = $getMerchandiseLocationResult->fetch_assoc()) {
                $locationArray[$getMerchandiseLocation['mer_loc_id']] = $getMerchandiseLocation['mer_loc_name'];
            }
        }

        $getMerchandiseGroupStmt = $conn->prepare('SELECT p_group_id, p_group_name FROM productgroupcategory');
        $getMerchandiseGroupStmt->execute();
        $getMerchandiseGroupResult = $getMerchandiseGroupStmt->get_result();

        if ($getMerchandiseGroupResult->num_rows > 0) {
            $groupArray = array();
            while ($getMerchandiseGroup = $getMerchandiseGroupResult->fetch_assoc()){ 
                $groupArray[$getMerchandiseGroup['p_group_id']] = $getMerchandiseGroup['p_group_name'];
            }
        }

    } catch (Exception $e) {
        header('Location: admin_dashboard.php');
    } catch (Error $e) {
        header('Location: admin_dashboard.php');
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('default_html_head.php'); ?>
    <link href="style/style-admin-merchandise.css" rel="stylesheet">
    <link href="style/style-table.css" rel="stylesheet">
    <title>Merchandise | Merchandises</title>
</head>
<body>

    <div class="alert alert-success" id="myAlert" role="alert">
        <div class="alert-message">
           
        </div>
        <button type="button" class="btn-close" id="id-close-noti"></button>
    </div>

    <?php include ('header.php'); ?>

    <!-- Add Stock Popup -->
    <div class="modal fade" id="id-addStockBox" tabindex="-1" role="dialog" aria-labelledby="addStockBoxLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-add-stock" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title" id="id-addStockTitle">Add Stock</h5>
                    <div class="dropdown" style="margin-bottom: 5px">
                        <button class="btn dropdown-toggle btn-dropdown" style="width: 100%" type="button" id="id-add-stock-dropdown-location" data-bs-toggle="dropdown"  aria-haspopup="true" value="0" aria-expanded="false">Select location:</button>
                        <div class="dropdown-menu dropdown-list div-add-stock dropdown-limit" style="width: 100%;" aria-labelledby="dropdownMenuButton" >
                            <?php
                                if (!empty($locationArray)) {
                                    foreach ($locationArray as $code => $name) {
                                        echo '<a class="dropdown-item" id="add-dropdown-item" data-code="' . $code .'">' . $name . '</a>';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <p style="font-size: var(--tableHeader); color: var(--color-65-black); margin-top: 15px; margin-bottom: 0" >Current Quantity: <span id="id-current-quantity"></span> <span id="id-span-addstock-inactive-warning" style="font-size: var(--btnSize); color: #ff0f0f; display: none;">Inactive</span></p>
                    <div class="input-group mb-3">
                        <input type="text" class="form-control txtInput" placeholder="Enter Quantity" aria-label="Add Quantity" id="id-txtAddStock-input" autocomplete="off" aria-describedby="basic-addon2" onKeyPress="return isNumber(event)">
                    </div>
                    <div class="modal-addon-1">
                        <button type="button" class="btn btn-gray-style btn-popup-style" data-dismiss="modal"   id="id-addStock-cancel">Cancel</button>
                        <button type="button" class="btn btn-default-style btn-popup-style" onclick="addStock(event)" id="id-addStock-add">Add</button>
                        <button id="id-btn-open-confirm" data-bs-target="#id-box-confirm-transfer" data-bs-toggle="modal" hidden></button>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <!-- End Add Stock Popup -->

    <!-- Confirm Stock Transfer -->
    <div class="modal fade" id="id-box-confirm-transfer" aria-hidden="true" aria-labelledby="confirmTransferLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <p id="id-p-confirm-change">Confirm Changes?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style btn-popup-style" data-bs-target="#id-addStockBox" data-bs-toggle="modal">Cancel</button>
                    <button type="button" class="btn btn-default-style btn-popup-style" onclick="addConfirmStock(event)" id="id-addStock-add-confirm">Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Confirm Stock Transfer -->

    <!-- Confirm Delete Transfer -->
    <div class="modal fade" id="id-box-confirm-delete" aria-hidden="true" aria-labelledby="confirmTransferLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <p id="id-p-confirm-remove">Remove Merchandise(s)?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style btn-popup-style" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-default-style btn-popup-style" id="id-btn-delete-merchandise-confirm">Remove</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Confirm Delete Transfer -->

    <!-- Confirm Delete Category -->
    <div class="modal fade" id="id-box-confirm-delete-category" aria-hidden="true" aria-labelledby="deleteCategoryLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <p id="id-p-confirm-remove-category"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style btn-popup-style" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-default-style btn-popup-style" id="id-btn-confirm-delete-category">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Confirm Delete Category -->

    <!-- Confirm Return Product -->
    <div class="modal fade" id="id-box-confirm-return-product" aria-hidden="true" aria-labelledby="returnProductLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <p id="id-p-confirm-return-product">Confirm Return <span id="id-span-return-product-name"></span> from <span id="id-span-return-product-location"></span> to HQ?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style btn-popup-style" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-default-style btn-popup-style" id="id-btn-confirm-return-product">Confirm</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Confirm Reeturn Product -->

    <!-- Multipurpose Alert Popup -->
    <div class="modal fade" id="id-box-mp-alert" aria-hidden="true" aria-labelledby="quantityErrorLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-body">
                    <p id="id-p-mp-alert" style="text-align: center; margin-top: 15px;">Quantity not enough!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style btn-popup-style"  data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Multipurpose Alert Popup -->

    <!-- Popup Box -->
    <div class="modal modal-done-request fade" id="id-request-done" tabindex="-1" role="dialog" aria-labelledby="requestDoneLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered justify-content-center" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="swal-icon swal-icon--success">
                        <span class="swal-icon--success__line swal-icon--success__line--long"></span>
                        <span class="swal-icon--success__line swal-icon--success__line--tip"></span>

                        <div class="swal-icon--success__ring"></div>
                        <div class="swal-icon--success__hide-corners"></div>
                    </div>
                    <p class="items-list" style="text-align: center;" id="id-p-success">Request is sent!</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-gray-style close-popup" data-bs-dismiss="modal" id="id-btn-close-popup">Close</button>  
                </div>
            </div>
        </div>
    </div>
    <!-- End Popup Box -->

    <!-- View Product Popup -->
    <div class="modal fade viewEditBox" id="id-viewEditBox" tabindex="-1" role="dialog" aria-labelledby="viewEditBoxLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-view-product" role="document">
            <div class="modal-content">
                <div class="modal-header justify-content-between">
                    <h5 class="modal-title" id="id-viewEditBox-title">View Product ID: #<span class="productId" id="id-title-productId"></span></h5>
                    <label class="switch" id="id-view-lbl-slider">
                        <input type="checkbox" id="id-view-input-slider" onchange="setProductStatus(event)" disabled>
                        <span class="slider round"></span>
                    </label>
                </div>
                <div class="modal-body row">
                    <div class="left-side col">
                        <label for="lbl-product-name">Name</label>
                        <input type="text" class="form-control txtInput txtInputEnable" id="id-txt-view-name" aria-describedby="productName" placeholder="Enter product name" required>
                        <label for="lbl-product-category">Category</label>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle btn-dropdown btn-dropdown-view" type="button" id="id-view-dropdown-category" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select category:</button>
                            <div class="dropdown-menu dropdown-list dropdown-limit" aria-labelledby="dropdownMenuButton">
                                <?php
                                    if (!empty($categoryArray)) {
                                        foreach ($categoryArray as $code => $name) {
                                            echo '<a class="dropdown-item dropdown-category" id="add-dropdown-item" data-code="' . $code .'">' . $name . '</a>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                        <label for="lbl-product-category">Group</label>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle btn-dropdown btn-dropdown-view" type="button" id="id-view-dropdown-group" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select group:</button>
                            <div class="dropdown-menu dropdown-list dropdown-limit" aria-labelledby="dropdownMenuButton">
                                <?php
                                    if (!empty($groupArray)) {
                                        foreach ($groupArray as $code => $name) {
                                            echo '<a class="dropdown-item dropdown-group id="add-dropdown-item" data-code="' . $code .'">' . $name . '</a>';
                                        }
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="right-side col">
                    <div class="right-side-quantity" id="id-div-right-side-quantity">
                        <label for="lbl-product-category" id="id-view-txt-quantity">Quantity <span id="id-span-quantity-inactive-warning" style="font-size: var(--btnSize); color: #ff0f0f; display: none;">Inactive</span></label>
                        <div class="dropdown" style="margin-bottom: 1rem">
                            <button class="btn dropdown-toggle btn-dropdown btn-dropdown-view" type="button" id="id-view-dropdown-location" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select location:</button>
                            <div class="dropdown-menu dropdown-list dropdown-limit" aria-labelledby="dropdownMenuButton">
                                <?php
                                    if (!empty($locationArray)) {
                                        foreach ($locationArray as $code => $name) {
                                            echo '<a class="dropdown-item dropdown-location id="add-dropdown-item" data-code="' . $code .'">' . $name . '</a>';
                                        }
                                    }
                                ?>
                            </div>  
                        </div>
                        <input type="text" class="form-control txtInput" id="id-txt-view-quantity" onKeyPress="return isNumber(event)" autocomplete="off" aria-describedby="Quantity" placeholder="Enter product quantity" required>
                    </div>

                        <label for="lbl-product-desc">Description</label>
                        <textarea class="txtarea-viewProduct textarea-default-style txtInput txtInputEnable"  id="id-txt-view-desc" rows="3" style="resize: none;"></textarea>
                    </div>
                    <hr>
                    <div class="product-img-div">
                        <img src="" class="img-thumbnail" alt="product_image" id="id-view-img">
                        <div class="mb-3">
                            <input class="form-control txtInput txtInputEnable" style="font-size: var(--tableHeader);" type="file" accept="image/*" id="id-img-upload">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style" data-dismiss="modal" id="id-btn-view-close">Close</button>
                    <button type="button" class="btn btn-default-style" id="id-btn-view-edit-save">Edit</button>
                    <button type="button" class="btn btn-default-style" id="id-btn-add-new">Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End View Product Popup -->

    <!-- Add Product Popup -->
    <div class="modal fade" id="id-add-product-box" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-add-product ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="id-add-title">Add New Merchandise</h5>
                </div>
            
                <div class="modal-body">
                    <label for="lbl-product-name">Name</label>
                    <input type="text" class="form-control txtInput txtInputEnable bar-default-style" id="id-txt-add-name" aria-describedby="productName" placeholder="Enter product name" required>
                    <br>
                    <label for="lbl-product-quantity">Quantity</label>
                    <input type="text" class="form-control txtInput txtInputEnable bar-default-style" id="id-txt-add-quantity" aria-describedby="productName" autocomplete="off"  onKeyPress="return isNumber(event)" placeholder="Enter product quantity" required>
                    <br>
                    <label for="lbl-product-category">Category</label>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle btn-dropdown btn-dropdown-add" style="width: 100%" type="button" id="id-add-dropdown-category" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select category:</button>
                        <div class="dropdown-menu dropdown-list dropdown-limit" style="width: 100%" aria-labelledby="dropdownMenuButton">
                            <?php
                                if (!empty($categoryArray)) {
                                    foreach ($categoryArray as $code => $name) {
                                        echo '<a class="dropdown-item add-dropdown-category" id="add-dropdown-item" data-code="' . $code .'">' . $name . '</a>';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <br>
                    <label for="lbl-product-category">Group</label>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle btn-dropdown btn-dropdown-add" style="width: 100%" type="button" id="id-add-dropdown-group" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Select group:</button>
                        <div class="dropdown-menu dropdown-list dropdown-limit" style="width: 100%" aria-labelledby="dropdownMenuButton">
                            <?php
                                if (!empty($groupArray)) {
                                    foreach ($groupArray as $code => $name) {
                                        echo '<a class="dropdown-item add-dropdown-group id="add-dropdown-item" data-code="' . $code .'">' . $name . '</a>';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                    <br>
                    <label for="lbl-product-desc">Description</label>
                    <textarea class="txtarea-viewProduct textarea-default-style txtInput txtInputEnable bar-default-style"  id="id-txt-add-desc" rows="3" style="resize: none;"></textarea>
                
                    <div class="product-img-div">
                    <br>
                        <div class="mb-3">
                            <input class="form-control txtInput txtInputEnable bar-default-style" style="font-size: var(--tableHeader);" type="file" accept="image/*" id="id-img-add-upload">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-default-style" id="id-btn-add-add">Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add Product Popup -->

    <!-- Add Category/Group/Location Popup -->
    <div class="modal fade" id="id-add-category-box" tabindex="-1" aria-labelledby="addCategoryGroupLocationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-add-product ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="id-add-category-title"></h5>
                </div>
                <div class="modal-body">
                    <div class="div-add-category-name-body" id="id-div-add-category-name-body">
                        <label for="lbl-category-name">Name</label>
                        <input type="text" class="form-control txtInput txtInputEnable bar-default-style" id="id-txt-add-category-name" aria-describedby="categoryGroupLocationName" autocomplete="off" placeholder="" required>
                        <br>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-default-style" id="id-btn-add-category">Add</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Add Category/Group/Location Popup -->

    <!-- Start Remove Category/Group/Location Popup -->
    <div class="modal fade" id="id-remove-category-box" tabindex="-1" aria-labelledby="removeCategoryGroupLocationLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-add-product ">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="id-remove-category-title"></h5>
                </div>
                <div class="modal-body">
                    <div class="div-remove-category-name-body" id="id-div-remove-category-name-body">
                        <div class="dropdown">
                            <button class="btn dropdown-toggle btn-dropdown" type="button" id="id-remove-category-btn-dropdown" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width: 100%">Check</button>
                            <div class="dropdown-menu dropdown-list dropdown-limit" id="id-remove-category-ul" style="width: 100%">
                                
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-default-style" id="id-btn-remove-category" disabled>Remove</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Remove Category/Group/Location Popup -->

    <!-- Start Return Product Popup -->
    <div class="modal fade" id="id-box-return-product" aria-hidden="true" aria-labelledby="quantityErrorLabel" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="id-title-return-product">Return <span id="span-title-return-product"></span> Stock To HQ</h5>
                    
                </div>
                <div class="modal-body">
                    <div class="dropdown" style="margin-bottom: 5px">
                        <button class="btn dropdown-toggle btn-dropdown" style="width: 100%" type="button" id="id-btn-dropdown-return-product" data-bs-toggle="dropdown"  aria-haspopup="true" value="0" aria-expanded="false">Select location:</button>
                        <div class="dropdown-menu dropdown-list div-return-product dropdown-limit" id="id-div-return-product"  style="width: 100%;" aria-labelledby="dropdownMenuButton" >
                            <?php
                                // if (!empty($locationArray)) {
                                //     foreach ($locationArray as $code => $name) {
                                //         if ($code != 1 ) {
                                //             echo '<a class="dropdown-item" id="return-dropdown-item" data-code="' . $code .'">' . $name . '</a>';
                                //         }
                                //     }
                                // }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style btn-popup-style" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-default-style btn-popup-style" id="id-btn-return-product">Return</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Return Product Popup -->

    <main>
        <?php include ('top_section.php'); ?>
        <section class="sect-admin-merchandise">
            <div class="admin-merchandise-nav row ">
                <div class="search-filter col">
                    <form action="" id="id-searchForm">
                        <div class="search-group">
                            <input type="text" class="search-bar" placeholder="Search">
                            <a href="#" class="search_icon"><i class="bi bi-search"></i></a>
                        </div>
                    </form>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle btn-dropdown" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Category</button>
                        <div class="dropdown-menu dropdown-list custom-scollbar" style="max-height: 200px; overflow-y: auto"  aria-labelledby="dropdownMenuButton">
                            <?php
                                echo '<a class="dropdown-item dropdown-active" id="dropdown-item" data-code="all">All</a>';
                                // if (mysqli_num_rows($getCategoryResult) > 0) {
                                //     echo '<a class="dropdown-item dropdown-active" id="dropdown-item" data-code="all">All</a>';
                                //     while ($getCategory = $getCategoryResult->fetch_assoc()) {
                                        
                                //         echo '<a class="dropdown-item" id="dropdown-item" data-code="' . $getCategory['product_cate_id'] .'">' . $getCategory['cate_name'] . '</a>';
                                //     }
                                // }
                                if (!empty($categoryArray)) {
                                    foreach ($categoryArray as $code => $name) {
                                        echo '<a class="dropdown-item" id="dropdown-item" data-code="' . $code .'">' . $name . '</a>';
                                    }
                                }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="add-remove-btn col">
                <!-- <i class="bi bi-file-earmark-plus"></i> -->
                    <div class="btn-group add-div-list" id="id-add-div-list">
                        <button type="button" class="btn btn-primary btn-default-style" style="border-top-right-radius: 20px; border-bottom-right-radius: 20px; background-color: var(--main-color)" data-bs-toggle="dropdown" aria-expanded="false" id="id-add-new-merchandise"><span>Add</span><i class="bi bi-file-earmark-plus"></i></button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item li-style" data-id="1">Merchandise</a></li>
                            <li><a class="dropdown-item li-style" data-id="2">Category</a></li>
                            <li><a class="dropdown-item li-style" data-id="3">Group</a></li>
                            <li><a class="dropdown-item li-style" data-id="4">Location</a></li>
                        </ul>
                    </div>
                    <div class="btn-group remove-div-list">
                        <button type="button" class="btn btn-gray-style" style="border-top-right-radius: 20px; border-bottom-right-radius: 20px; background-color: var(--btnGray)" data-bs-toggle="dropdown" aria-expanded="false" id="id-add-remove-merchandise"><span>Remove</span></i></button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item li-style disabled" id="id-remove-merchandise">Merchandise</a></li>
                            <li><a class="dropdown-item li-style remove-list" data-id="1" >Category</a></li>
                            <li><a class="dropdown-item li-style remove-list" data-id="2" >Group</a></li>
                        </ul>
                        <!-- <button type="button" class="btn btn-primary btn-gray-style" id="id-remove-merchandise" disabled><span>Remove merchandise</span><i class="bi bi-trash"></i></button> -->
                    </div>
                    
                </div>
            </div>
            <div class="merchandise-list">            
                <div class="table-responsive-lg tbl-req">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <div>
                                        <input class="form-check-input" type="checkbox" id="id-top-checkbox" value="" aria-label="...">
                                    </div>
                                </th>
                                <th scope="col">Photo</th>
                                <th scope="col">Name</th>
                                <th scope="col">Date Added</th>
                                <th scope="col">Category</th>
                                <th scope="col">Group</th>
                                <th scope="col">Quantity</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- <tr>
                                <td>
                                    <div>
                                        <input class="form-check-input" type="checkbox" id="checkboxNoLabel" value="" aria-label="...">
                                    </div>
                                </td>
                                <td><img src="assets/img/merchandise/bag.png" alt="" width="40px" height="40px"></td>
                                <td>TVS | Bag</td>
                                <td>27/2/2024</td>
                                <td>6</td>
                                <td>Bag</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="hidden-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></a>
                                        <div class="dropdown-menu dot-dropdown" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">Add Stock</a>
                                            <a class="dropdown-item" href="#">View</a>
                                        </div>
                                    </div>
                                </td>
                            </tr> -->
                        </tbody>
                    </table>
                </div>
                <div class="pagination">
                    <span id="id-pagination-previous" class="previous"><</span>
                    <span id="id-pagination-current" class="current">1</span>
                    <span id="id-pagination-next" data-nextpage="<?php echo ($merchandiseTotal > 10) ? '1' : '0' ?>" class="next">></span>
                </div>
            </div>
        </section>
    </main>

    <script src="style/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    <script src="js/index.js" type="text/javascript"></script>
    <script src="js/admin_merchandise.js" type="text/javascript"></script>
    <?php include 'footer.php' ?>

</body>
</html>