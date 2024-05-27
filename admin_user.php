<?php
    $current_page = 'admin_user';
    $user_type = 'admin';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('default_html_head.php'); ?>
    <link href="style/style-table.css" rel="stylesheet">
    <link href="style/style-admin-user.css" rel="stylesheet">
    <title>Admin | User List</title>
</head>
<body>
    <?php include ('header.php'); ?>

    <main>
        <?php include ('top_section.php'); ?>

        <section class="sect-admin-user">
            <div class="admin-user-nav">

            </div>
            <div class="admin-user-list">
                <div class="table-responsive-lg tbl-req">
                    <table class="table">
                        <thead>
                            <tr>
                                <th scope="col">
                                    <div>
                                        <input class="form-check-input" type="checkbox" id="checkboxNoLabel" value="" aria-label="...">
                                    </div>
                                </th>
                                <th scope="col">Name</th>
                                <th scope="col">Unit</th>
                                <th scope="col">Date Registered</th>
                                <th scope="col">Phone</th>
                                <th scope="col">Email</th>
                                <th scope="col">Role</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div>
                                        <input class="form-check-input" type="checkbox" id="checkboxNoLabel" value="" aria-label="...">
                                    </div>
                                </td>
                                <td>Haji Abang Khairrudin</td>
                                <td>Marketing</td>
                                <td>27/2/2024</td>
                                <td>016-301-9163</td>
                                <td>khai@gmail.com</td>
                                <td>Admin</td>
                                <td>
                                    <div class="dropdown">
                                        <a class="hidden-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-three-dots-vertical"></i></a>
                                        <div class="dropdown-menu dot-dropdown" aria-labelledby="dropdownMenuButton">
                                            <a class="dropdown-item" href="#">Edit</a>
                                            <a class="dropdown-item" href="#">View</a>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </main>

    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    <script src="style/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>