
<?php 
    if ($current_page == 'cart') {
        $page_title = "Cart";
    } elseif ($current_page == 'index') {
        $page_title = "Merchandise";
    } elseif ($current_page == 'request') {
        $page_title = "Make a request";
    } elseif ($current_page == 'request_list') {
        $page_title = "My Request";
    } elseif ($current_page == 'admin_dashboard') {
        $page_title = "Admin Dashboard";
    } elseif ($current_page == "admin_request") {
        $page_title = "Request";
    } elseif ($current_page == "admin_request_detail") {
        $page_title = "Request Details";
    } elseif ($current_page == "admin_merchandise") {
        $page_title = "Merchandise";
    } elseif ($current_page == "admin_user") {
        $page_title = "User";
    } else {
        $page_title = "TVS | Bag";
    }
?>

<section class="sect-title">
    <div class="top-section align-items-center row">
        <div class="empty col-4">
            <?php 
            
                if ($current_page == 'index' || $current_page == 'request_list') {
                    echo '
                    <div class="dropdown dropdown-cate-type">
                        <button class="btn dropdown-toggle btn-dropdown" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ';
                            if ($current_page == 'index') {
                                echo 'Category';
                            } else {
                                echo 'Status';
                            }
                    echo '</button>
                        <div class="dropdown-menu dropdown-list dropdown-limit" aria-labelledby="dropdownMenuButton">' ?>
                            
                            <?php
                            if ($current_page == 'index') {
                                echo '<a class="dropdown-item dropdown-active" data-prod-cate="all">All</a> ';
                                if ($getCateResult->num_rows > 0) {
                                    while ($cateRow = $getCateResult->fetch_assoc()) {
                                        echo '<a class="dropdown-item" id="dropdown-item" data-prod-cate="' . $cateRow['product_cate_id'] . '">' . $cateRow['cate_name'] . '</a>';
                                    }
                                }
                            } else {
                                echo '<a class="dropdown-item request-dropdown dropdown-active" data-status="all">All</a>';
                                echo '<a class="dropdown-item request-dropdown" id="dropdown-item" data-status="0">Pending</a>';
                                echo '<a class="dropdown-item request-dropdown" id="dropdown-item" data-status="-1">Rejected</a>';
                                echo '<a class="dropdown-item request-dropdown" id="dropdown-item" data-status="1">Accepted</a>';
                            }
                            ?>
                        <?php echo '
                        </div>
                    </div>';

                    if ($current_page == 'index') {
                        $getAllLocationResult = getLocation($conn);
                        echo '
                            <div class="dropdown dropdown-cate-location ">
                                <button class="btn dropdown-toggle btn-dropdown" type="button" id="id-btn-dropdown-location" data-bs-toggle="dropdown" aria-haspopup="true" aria-expaded="false">
                                    Location
                                </button>
                                <div class="dropdown-menu dropdown-list dropdown-limit">';
                                
                                    if ($getAllLocationResult->num_rows > 0) {
                                       
                                        while ($getLocation = $getAllLocationResult->fetch_assoc()) {
                                            echo '<a class="dropdown-item request-dropdown" data-locationid="' . $getLocation['mer_loc_id'] .'">' . $getLocation['mer_loc_name'] . '</a>';
                                        }
                                    } else {
                                        echo 'No Location found!';
                                    }
                            echo '</div>
                            </div>
                        ';
                    }
                            
                } else {
                    echo ' ';
                }
            ?>

        </div>
        <div class="top-bar top-header col-4 ">
            <h4><?php echo $page_title ?></h4>
        </div>

        <?php
            if ($current_page == 'index' || $current_page == 'request_list') {
                    echo '<div class="search-filter col-4">
                            <form action="" id="id-searchForm" class="row justify-content-center">
                                <div class="search-group">
                                    <input type="text" class="search-bar" placeholder="'?> <?php echo ($current_page == 'index') ? 'Search by Name' : 'Search by ID' ?> <?php echo '">
                                    <a href="#" class="search_icon"><i class="bi bi-search"></i></a>
                                </div>
                            </form>';
                if ($current_page == 'request_list') {
                    echo '
                    <button type="button" class="btn btn-primary btn-default-style" id="id-btn-new-request" style="border-top-right-radius: 20px; border-bottom-right-radius: 20px; background-color: var(--main-color)"><i class="bi bi-file-earmark-plus"></i></button>
                    ';
                }
                        echo '
                        </div>';
            }
        ?>

    </div>
</section>
