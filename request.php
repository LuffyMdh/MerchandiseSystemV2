<?php
    include 'server/connect.php';
    include 'server/user_session.php';
    $current_page = 'request';
    include 'server/admin_user_type_validation.php';
    // if ($_SESSION['totalCart'] == 0) {
    //     header('Location: index.php');
    // }

    try {
        $getLocationResult = getLocation($conn);
    } catch (Throwable $e) {
        echo $e;
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <?php include('default_html_head.php'); ?>
    <link href="style/style-request.css" rel="stylesheet">
    <title>TVS | Make Request</title>
</head>
<body>
    <div id="id-overlay" class="overlay hidden">
        <div class="overlay-content" style="text-align: center;">
            <img src = "assets/img/gif/pulse.svg" alt="Loading GIF"/>
            <p style="color: white; background-color: rgba(0,0,0, 0.6); padding: 10px;" id="id-overlay-message">Loading... summoning the power of zeros and ones.</p>    
        </div>
    </div>

    <?php include ('header.php'); ?>


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
                        <p class="items-list">Request is sent!</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-gray-style close-popup" data-dismiss="modal" id="id-btn-close-popup">Close</button>  
                    </div>
                </div>
            </div>
        </div>
        <!-- End Popup Box -->


    <main>
        <?php include ('top_section.php'); ?>

        <section class="sect-request row">
            <div class="col-lg-6">
                <div class="requestor-detail">
                    <h5>Requester</h5>
                    <div class="mb-4">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control requester-input user_fname" id="id-name" placeholder="John Doe" disabled>
                    </div>
                    <div class="mb-4">
                        <label for="email" class="form-label">Email address</label>
                        <input type="email" class="form-control requester-input user_email" id="id-email" placeholder="name@example.com" disabled>
                    </div>
                    <div class="mb-4">
                        <label for="phone-no" class="form-label">Phone No.</label>
                        <input type="text" class="form-control requester-input user_phone" id="id-phone-no" placeholder="0123456789" disabled>
                    </div>
                    <div class="mb-4">
                        <label for="department" class="form-label">Unit / Department</label>
                        <input type="text" class="form-control requester-input department" id="id-deparment" placeholder="Marketing" disabled>
                    </div>
                    <div class="mb-4">
                        <label for="department" class="form-label"></label>Requester Location <span style="color: red;">&#42;</span></label>
                        <div class="dropdown">
                        <button class="btn dropdown-toggle btn-dropdown" style="width: 100%;" type="button" id="id-btn-dropdown-location" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Choose location</button>
                        <div class="dropdown-menu dropdown-list dropdown-limit location-dropdown" style="width: 100%"  aria-labelledby="dropdownMenuButton">
                            <?php
                                if ($getLocationResult->num_rows > 0) {
                                    while ($getLocation = $getLocationResult->fetch_assoc()) {
                                        echo '<a class="dropdown-item" id="dropdown-item" data-location="' . $getLocation['mer_loc_id'] . '">' . $getLocation['mer_loc_name'] . '</a>';
                                    }
                                }

                            ?>
                        </div>
                    </div>
                    </div>
                    <div class="mb-4">
                        <label for="request-purpose" class="form-label">Purpose of Rquest <span style="color: red;">&#42;</span></label>
                        <span class="por-err-msg error-message">Required!</span>
                        <textarea class="form-control request-box" id="id-request-purpose" rows="3"></textarea>
                    </div>
                </div>
            

            </div>
            <div class="col-lg-6">                                                                                                                               
                <div>
                    <h5>Support Document <span style="color: red;">&#42;</span><span style="font-size: 0.5rem; vertical-align: top; color: red; margin-left: 2px">PDF/JPG/JPEG/PNG</span></h5>
                    <div class="div-support-attachment">
                        <div class="div-input-file">
                            <div class="input-file">
                                <input class="form-control fileAttachment" type="file" accept="image/jpeg, image/png, application/pdf" id="id-img-upload" onchange="validateFileInput(event)"><i class="bi bi-x" onclick="removeInputField(event)"></i>
                            </div>
                        </div>
                        <p class="p-add-document" id="id-add-document">Add More Documents</p>
                        <p class="p-max-document">Maximum no. of documents reached!</p>
                        <p class="doc-err-msg error-message">Upload at least 1 supporting document!</p>
                    </div>
                </div>
            </div>
            <div class="request-btn">
                    <div class="btn-request">
                        <button type="button" class="btn btn-primary btn-default-style btn-make-req">Make request</button>
                    </div>
                    <div class="return-btn">
                        <button type="button" id="id-btnReturn" class="btn btn-primary btn-gray-style btn-return">Return</button>
                    </div>
            </div>
      
        </section>
    </main>
    <script type="text/javascript" src="js/jquery-3.7.1.min.js"></script>
    <script src="style/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="js/make_request.js" type="text/javascript"></script>
    <?php
        include 'footer.php';
    ?>
</body>
</html>