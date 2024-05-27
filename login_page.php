<?php
    session_start();

    if (isset($_SESSION['loggedin'])) {
        header('Location: index.php');
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('default_html_head.php'); ?>
    <link href="style/style-login-page.css" rel="stylesheet">
    <title>Merchandise | Log In</title>
</head>
<body>


    <!-- Pop Up Box -->
    <div class="modal fade" id="loginError" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    Incorrent Email or Password!
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-gray-style" data-dismiss="modal" id="id-btn-close-popup">Close</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Pop Up Box -->

    <div class="container login-container row">
        <div class="login-img col">
            <img src="assets/img/login-img.png" alt="">
        </div>

        <div class="login-section user-form-section active col">
            <div class="div-logo">
                <img src="assets/img/smglogo.png" alt="">
                <h6>Merchandise System</h6>
                <p>Login</p>
            </div>
            <div class="login-form detail-form">
                <form>
                    <div class="form-group">
                        <label for="login-email">Email address</label>
                        <input type="email" class="form-control" id="id-login-email" aria-describedby="emailHelp" placeholder="Enter email" required>
                        <span class="email-error-msg error-msg">Please enter your email!</span>
                    </div>
                    <div class="form-group">
                        <label for="login-password">Password</label>
                        <input type="password" class="form-control" id="id-login-password" placeholder="Password" required>
                        <span class="pass-error-msg error-msg">Please enter your password!</span>
                    </div>
                    <button type="button" class="btn btn-primary btn-default-style" name="login" id="id-btn-login">Log In</button>
                </form>
            </div>
        </div>
<!--
        <div class="signup-section user-form-section col">
            <div class="div-logo">
                <img src="assets/img/smglogo.png" alt="">
                <h6>Merchadnise System</h6>
                <p>Register</p>
            </div>
            <div class="signup-form detail-form">
                <form action="" class="form row">
                    <div class="form-group col">
                        <label for="signup-name">Name</label>
                        <input type="text" class="form-control" id="id-signup-name" aria-describedby="emailHelp" placeholder="Enter name">
                    </div>
                    <div class="form-group col">
                        <label for="signup-staff-id">Staff ID</label>
                        <input type="text" class="form-control" id="id-signup-staff-id" placeholder="Enter staff ID">
                    </div>
                    <div class="form-group">
                        <label for="signup-email">Email</label>
                        <input type="email" class="form-control" id="id-signup-email" placeholder="Enter email">
                    </div>
                    <div class="form-group">
                        <label for="signup-passsword">Password</label>
                        <input type="password" class="form-control" id="id-signup-password" placeholder="Enter password">
                    </div>
                    <div class="form-group">
                        <label for="signup-phone">Phone No.</label>
                        <input type="text" class="form-control" id="id-signup-phone" placeholder="Enter phone no.">
                    </div>
                    <div class="form-group">
                        <label for="signup-passsword">Password</label>
                        <input type="password" class="form-control" id="id-password" placeholder="Enter password">
                    </div>
                    <button type="submit" class="btn btn-primary btn-default-style">Sign Up</button>
                    <p class="no-have-account">Already have an account? <span id="sign-in-acc" class="link-page"><a href="#">Sign In</a></span></p>
                </form>
            </div>
        </div>
    </div>
-->
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    <script src="style/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/function.js"></script>
    <script src="js/login.js" type="text/javascript"></script>
</body>
</html>