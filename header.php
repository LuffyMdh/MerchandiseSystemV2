<header>
    <nav class="navbar navbar-expand-lg py-1 shadow" aria-label="Thirteenth navbar example">
        <div class="container-fluid">
            <!-- Header -->
            <a class="navbar-brand" href="index.php">
                <img src="assets/img/smglogo.png" alt="Bootstrap" width="110" height="40" class="navbar-brand">
            </a>
            <button class="navbar-toggler second-button nav-burger" type="button" data-bs-toggle="collapse" data-bs-target="#navbarsExample11" aria-controls="navbarsExample11" aria-expanded="false" aria-label="Toggle navigation">
                <div class="animated-icon2"><span></span><span></span><span></span><span></span></div>
            </button>
            
            <div class="collapse navbar-collapse justify-content-md-center" id="navbarsExample11">
                <div class="container d-flex justify-content-center">
                    <ul class="navbar-nav">
                        <?php
                            if ($user_type == "normal") {
                                echo    '
                                        <li class="nav-item">
                                            <a class="nav-link'; ?> <?php echo $current_page  == "index" ? "active-tab" : ""; ?> <?php echo '"aria-current="page" href="index.php">Merchandise</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link'; ?> <?php echo $current_page  == "request_list" ? "active-tab" : ""; ?> <?php echo '"href="request_list.php">Request List</a>
                                        </li>
                                        ';
                            } else {
                                echo    '
                                        <li class="nav-item">
                                            <a class="nav-link'; ?> <?php echo $current_page  == "admin_request" ? "active-tab" : ""; ?> <?php echo '"aria-current="page" href="admin_request.php">Request</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link'; ?> <?php echo $current_page  == "admin_dashboard" ? "active-tab" : ""; ?> <?php echo '"href="admin_dashboard.php">Dashboard</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link'; ?> <?php echo $current_page  == "admin_merchandise" ? "active-tab" : ""; ?> <?php echo '"href="admin_merchandise.php?categoryCode=all">Merchandise</a>
                                        </li>
                                        ';
                            }
                        ?>

                    </ul>  
                </div>
                <div class="nav-icon">
                </div>
                <ul class="navbar-nav user-msg-icon">
                    <li class="nav-item py-1 px-4 m-1">
                        <div class="dropdown">
                            <a class="hidden-arrow" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-person-circle h3"></i></a>
                            <ul class="dropdown-menu dropdown-user" aria-labelledby="dropdownMenuicon">
                                <li><a class="dropdown-item"> Hi, <?php echo $_SESSION['loggedin_name'] ?></a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item btn-logout" href="#">Logout</a></li>
                            </ul>
                        </div>
                    </li>
                </ul>

            </div>
        </div>
    </nav>
</header>
