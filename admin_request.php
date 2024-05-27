<?php
    include 'server/connect.php';
    include 'server/user_session.php';
    
    $current_page = 'admin_request';

    if (!isset($_GET['catecode'])) {
        //header('Location: admin_request.php');
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('default_html_head.php'); ?>
    <link href="style/style-admin-req.css" rel="stylesheet">
    <link href="style/style-table.css" rel="stylesheet">
    <title>Merchandise | Request</title>
</head>
<body class="custom-scrollbar">
    <?php include ('header.php'); ?>
    <main>
        <?php include ('top_section.php'); ?>
        <section class="sect-admin-req">
            <div class="container justify-content-center req-nav">
                <div class="row mini-icon justify-content-center mx-2">
                    <div class="col icon-pending icon-div" onclick='changeStatusList(event, "pending")'>
                        <h6 class="h6-pending"><span class="status-icon span-pending pending" ></span><span class="status-span-word">Pending</span></h6>
                    </div>
                    <div class="col icon-approved icon-div" onclick='changeStatusList(event, "accepted")'>
                        <h6 class="h6-approved"><span class="status-icon span-approved accepted" ></span><span class="status-span-word">Approved</span></h6>
                    </div>
                    <div class="col icon-rejected icon-div" onclick='changeStatusList(event, "rejected")'> 
                        <h6 class="h6-rejected"><span class="status-icon span-rejected rejected" ></span><span class="status-span-word">Rejected</span></h6>
                    </div>
                    <div class="col icon-all icon-div" onclick='changeStatusList(event, "all")'>
                        <h6 class="h6-all"><span class="status-icon span-all all" ></span>All</h6>
                    </div>
                    <div class="col-12 seach-bar">
                    </div>
                </div>

            </div>
            <div class="table-responsive-lg tbl-req">
                <table class="table ">
                    <thead>
                        <tr>
                            <th scope="col">Request ID</th>
                            <th scope="col">Date Created</th>
                            <th scope="col">Total Item</th>
                            <th scope="col">Status</th>
                            <th scope="col">Location</th>
                            <th scope="col">User</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="pagination">
                <span class="previous"><</span>
                <span class="current">1</span>
                <span class="next">></span>
            </div>
        </section>
    </main>
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    <script src="style/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/function.js" type="text/javascript"></script>
    <script src="js/admin_request_list.js" type="text/javascript"></script>
    <?php include 'admin_footer.php' ?>
</body>
</html>