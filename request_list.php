<?php
    include 'server/connect.php';
    include 'server/user_session.php';
    $current_page = 'request_list';
    include 'server/admin_user_type_validation.php';
    $sqlCountRecord = $conn->prepare("SELECT COUNT(*) AS totalRecord
                                        FROM request
                                        WHERE request.user_id = ?;");
    $sqlCountRecord->bind_param("s", $_SESSION['loggedin']);
    $sqlCountRecord->execute();
    $countResult = $sqlCountRecord->get_result();
    $totalRecord = $countResult->fetch_assoc();
    $pages = ceil($totalRecord['totalRecord'] / 10);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('default_html_head.php'); ?>
    <link href="style/style-req-list.css" rel="stylesheet">
    <link href="style/style-table.css" rel="stylesheet">
    <title>TVS | Request list</title>
</head>
<body>
    <!-- Merchandise Comment Popup  !-->
    <div class="modal fade" id="id-modal-comment" tabindex="-1" role="dialog" aria-labelledby="commentMerchandiseLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content" style="height: 500px">
                <div class="modal-header">
                    <h5 class="modal-title">Merchandise Update Overview</h5>
                </div>
                <div class="modal-body" id="id-modal-body-comment" style="overflow: auto">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray-style" id="id-btn-close-comment" data-bs-target="#viewPopup" data-bs-toggle="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Merchandise Comment Popup  !-->

    <?php include ('header.php'); ?>

    <div class="modal-popup modal-request-detail">

    </div>

    <main>
        <?php include ('top_section.php'); ?>

        <section class="sect-req-list row">
            <div class="table-responsive-lg tbl-req">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">Request ID</th>
                            <th scope="col"><span class="tbl-col-date" id="id-tbl-col-date" data-sort="DESC">Date Created</span></th>
                            <th scope="col">Location</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody class="table-body">

                    </tbody>
                </table>
            </div>

            <div class="pagination">
                <span class="previous disabled-pagination"><</span>
                <span class="current">1</span>
                <span class="next">></span>
            </div>
        </section>
    </main>
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    <script src="style/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/request_list.js"></script>
    <?php
        include 'footer.php';
    ?>
</body>
</html>