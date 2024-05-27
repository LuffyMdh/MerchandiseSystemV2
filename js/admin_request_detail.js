
let productArray = [];
let requesterId;
let txtRejectAmount;
let tempReject;

window.onload = () => {
    let btnCloseRejectionReason = document.getElementById('btn-close-box');
    let btnCloseComment = document.getElementById('id-btn-close-comment');
    let btnCloseAlertQuan = document.getElementById('id-btn-close-alert-not-enough');
    const btnCloseConfirmDate = document.getElementById('id-btn-cancel-date-picker');
    const rejectMsgBox = document.getElementById('id-reject-message-text');


    const btnMerchandise = document.getElementById('id-btn-merchandise');
    const btnRemoveMerchandise = document.getElementById('id-btn-remove-merchandise');

    const btnRequestReject = document.getElementById('id-btn-request-reject');
    const txtRejectReason = document.getElementById('id-txt-reject-reason-textarea');
    const btnRequestRejectConfirm = document.getElementById('id-btn-reject-confirm');

    const btnAcceptRequest = document.getElementById('id-btn-confirm-date');
    const txtInputDateTime = document.getElementById('datetimepicker');
    const txtAcceptComment = document.getElementById('id-txt-accept-reason-textarea');

    let productDetails = document.querySelectorAll('.product-detail');

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const urlRequestId = urlParams.get('request_id')
    const urlStatus = urlParams.get('status');
    const urlLocation = urlParams.get('location');

    const tbody = document.getElementById('id-table-tbody');
  
    const btnPgNext = document.getElementById('id-pagination-next');
    const currentPgHTML = document.getElementById('id-pagination-current');
    const btnPgPrev = document.getElementById('id-pagination-previous')

    const overlayBox = document.getElementById('id-overlay');

    const groupFilterList = document.querySelectorAll('.req-detail-header .request-dropdown');

    let offset = 0;
    let currentPg = 1;

    txtAmount = document.getElementById('id-txt-add-amt');


    productDetails.forEach(product => {
        let proCode = product.dataset.code;
        let quan = product.dataset.quan;
        productArray.push({
            proCode: proCode,
            quan: quan 
        });
    })


    btnMerchandise.addEventListener('click', (e) => {

        let productId = e.target.dataset.product;
        let requestId = e.target.dataset.request;
        let operationType = e.target.dataset.operation;


        amount = txtAmount.value.trim();
        amount = parseInt(amount);

        if (amount == '' || isNaN(amount) || amount <= 0) {
            txtAmount.classList.add('error-input');
            return;
        }
        merchandiseOperation(productId, requestId, operationType, amount);

    })
    
    btnCloseRejectionReason.addEventListener('click', () => {
        $('#id-reject-reason').modal('hide');
        
    })

    btnCloseComment.addEventListener('click', () => {
        $('#id-modal-comment').modal('hide');
    })

    btnCloseAlertQuan.addEventListener('click', () => {
        $('#id-modal-alert-not-enough').modal('hide');
        window.location.reload();
    })
    
    btnCloseConfirmDate.addEventListener('click', () => {
        $('#id-modal-date-picker').modal('hide');
    })

    jQuery('#datetimepicker').datetimepicker({
        format:'d/m/Y H:i',
        minDate:'-1970/01/01'
    });

    txtAmount.addEventListener('click', (e) => {
        if (e.target.classList.contains('error-input')) {
            e.target.classList.remove('error-input');
        }
    })

    $('#id-add-amount').on('hidden.bs.modal', function () {

        btnMerchandise.removeAttribute('data-product');
        btnMerchandise.removeAttribute('data-request');
        btnMerchandise.removeAttribute('data-operation');
        txtAmount.classList.remove('error-input');
        txtAmount.value = '';
    });

    $('#id-modal-remove-confirmation').on('hidden.bs.modal', () => {
        btnRemoveMerchandise.removeAttribute('data-product');
        btnRemoveMerchandise.removeAttribute('data-request');
        btnRemoveMerchandise.removeAttribute('data-operation');

    });

 
    requesterId = document.querySelector('.req-name').dataset.id;


    /* Start Reject Procedure */

    $('#id-reject-reason').on('hidden.bs.modal', () => {
        txtRejectReason.classList.remove('error-input');
        txtRejectReason.value = '';
    })

    txtRejectReason.addEventListener('click', () => {
        txtRejectReason.classList.remove('error-input');
    })

    function confirmRejectRequest(e) {
        rejectReasonValue = txtRejectReason.value.trim();

        if (rejectReasonValue == '' ) {
            txtRejectReason.classList.add('error-input');
            return;
        }
        urlRequestId
        requestEvaluate(urlRequestId, -1, rejectReasonValue, null);
    } 

    function rejectRequest() {
        txtRejectReason.value = '';
        
        $('#id-reject-reason').modal('show');
        
        $('#id-reject-reason').on('shown.bs.modal', function () {
            txtRejectReason.focus();
        });
    
    
        btnRequestRejectConfirm.addEventListener('click', confirmRejectRequest);

    }

    function getRandomInt(max) {
        return Math.floor(Math.random() * max);
    }
    

    function requestEvaluate(requestId, type, reason, pickupDateTime) {
        $('#id-modal-date-picker').modal('hide');
        
        const message = [
            "Initializing quantum flux... preparing for digital transcendence.",
            "Activating cybernetic conduits... channeling the essence of the digital realm.",
            "Energizing the digital matrix... syncing with the heartbeat of the internet.",
            "Navigating the digital cosmos... surfing the binary tides.",
            "Connecting with the virtual ether... diving into the sea of code."
        ];

        document.body.scrollTop = 0; // For Safari
        document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera

        const pLoadingMessage = document.getElementById("id-overlay-message");
        pLoadingMessage.innerHTML= message[getRandomInt(5)];

        document.body.classList.add('overflow-hidden');
        overlayBox.classList.remove('hidden');

        $('#id-reject-reason').modal('hide');

        $.ajax({
            url: 'server/db_admin_request_evaluation.php',
            type: 'POST',
            data: { type: type,
                    requestId: requestId,
                    reason: reason,
                    pickupTime: pickupDateTime},
            dataType: 'json',
            success: (result) => {
                console.log(result);
                let returnCode = result.returnCode;

                if (returnCode == 200) {
                    overlayBox.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    $('#p-done-message').html('Request accepted.');
                    $('#id-request-done').modal('show');
                    
                } else if (returnCode == 201) {
                    overlayBox.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    $('#p-done-message').html('Request rejected.');
                    $('#id-request-done').modal('show');
                } else if (returnCode == 400){ 
                    overlayBox.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                    $('#p-done-message').html('Request accepted.');
                    $('#id-p-mp-alert').html(result.message);
                    $('#id-box-mp-alert').modal('show');
                }
        
            }
        })
    }

    $('#id-request-done').on('hidden.bs.modal', () => {
        location.replace(location.href);
    })



    btnRequestReject.addEventListener('click', rejectRequest);

    /* End Reject Procedure */

    /* Start Accept Request Procedure */

    $('#id-modal-date-picker').on('hidden.bs.modal', () => {
        txtInputDateTime.value = '';
        txtInputDateTime.classList.remove('error-input');

        txtAcceptComment.value = '';
        txtAcceptComment.classList.remove('error-input');
    
    })

    txtInputDateTime.addEventListener('click', () => {
        txtInputDateTime.classList.remove('error-input');
    })

    txtAcceptComment.addEventListener('click', () => {
        txtAcceptComment.classList.remove('error-input');
    })

    btnAcceptRequest.addEventListener('click', (e) => {
        txtInputDateTimeValue = txtInputDateTime.value.trim();
        txtAcceptCommentValue = txtAcceptComment.value.trim();
        let txtDateTimeValue = $('#datetimepicker').datetimepicker('getValue');
        let pickupDate = txtDateTimeValue.getDate() + '/' + (txtDateTimeValue.getMonth() + 1) + '/' + txtDateTimeValue.getFullYear();
        let pickupTime = txtDateTimeValue.getHours() + ":" + txtDateTimeValue.getMinutes();
    
        let dateTime = pickupDate + ' ' + pickupTime;
    
        const date = new Date();
        date.setHours(date.getHours(), date.getMinutes(), 0, 0);
    
        let milliPickup = txtDateTimeValue.getTime();
        let milliCurrent = date.getTime();
        proceedBoolean = true;

        if (txtInputDateTimeValue == '') {
            txtInputDateTime.classList.add('error-input');
            proceedBoolean = false;
        }

        if (milliPickup <= milliCurrent) {
            txtInputDateTime.classList.add('error-input');
            proceedBoolean = false;
        }

        if (proceedBoolean) {
            requestEvaluate(urlRequestId, 1, txtAcceptCommentValue, dateTime);
            
        }
    })
    /* End Accept Request Procedure  */

    /* Start Pagination */

    function enabledBtn(btn) {
        btn.classList.remove('disabled-pagination');
        btn.style.pointerEvents = 'auto';
    }
    
    function disabledBtn(btn) {
        btn.classList.add('disabled-pagination');
        btn.style.pointerEvents = 'none';
    }

    btnPgNext.addEventListener('click', () => {
        window.scrollTo(0, 450);
        paginationOperation(1);

    });

    btnPgPrev.addEventListener('click', () => {
        window.scrollTo(0, 450);
        paginationOperation(2);
    })

    function paginationOperation(type) {
        

        switch (type) {
            case 1:
                currentPg += 1;
                break;
            case 2:
                currentPg -= 1;
        }
        offset = ((currentPg - 1) * 10);

        if (currentPg == 1) {
            disabledBtn(btnPgPrev);
        } else {
            enabledBtn(btnPgPrev);
        }
        currentPgHTML.innerHTML = currentPg;
        displayQuantity(offset);
    }

    /* End Pagination */

    let selectedGroup = null;
    /* Start Display Merchandise */
    function displayQuantity(offset) {
        $.ajax({
            url: 'server/db_admin_get_request_merchandise.php',
            type: 'POST',
            data: { requestId:  urlRequestId,
                    statusCode: urlStatus,
                    locationCode: urlLocation,
                    offset: offset,
                    groupId: selectedGroup
            },
            dataType: 'json',
            success: (result) => {
         
                // console.log(result);
                let returnCode = result.returnCode;
                let totalProduct = result.totalProduct;
                
                if (returnCode == 200) {
                    tbody.innerHTML = result.html;
                }


                if (totalProduct > 10) {
                    enabledBtn(btnPgNext);
                } else {
                    disabledBtn(btnPgNext);
                }

                if (currentPg != 1) {
                    enabledBtn(btnPgPrev);
                } else {
                    disabledBtn(btnPgPrev)
                }
                
            }
        })
    }
    /* End Display Merchandise */
    const btnDropdownGroup = document.getElementById('id-btn-dropdown-group');
    let activeDropdownBtn = '';
    /* Start Group Filter */
    groupFilterList.forEach(group => {
        group.addEventListener('click', (e) => {
            window.scrollTo(0, 450);
            currentPg = 1;
            currentPgHTML.innerHTML = currentPg;
            e.target.classList.add('dropdown-active');

            if (activeDropdownBtn !== '') {
                activeDropdownBtn.classList.remove('dropdown-active');
            }
           
            
            activeDropdownBtn = e.target;
            btnDropdownGroup.innerHTML = e.target.innerHTML;
            let groupId = e.target.dataset.groupid;
            selectedGroup = groupId;
            offset = 0;
            displayQuantity(offset);
        })
    })

    /* End Group Filter */

    disabledBtn(btnPgNext);
    disabledBtn(btnPgPrev);

    displayQuantity(offset);
}





function merchandiseOperation(productId, requestId, operationType, amount) {
    $.ajax({
        url: 'server/admin_add_to_request.php',
        type: 'POST',
        data: { productId: productId,
                requestId: requestId,
                amount: amount,
                operationType: operationType
        },
        dataType: 'json',
        success: (result) => {
            let returnCode = result.returnCode;
            let  message = result.message;



            if (returnCode == 200) {
                window.location.reload();
            } else if (returnCode == 400) {
                $('#id-add-amount').modal('hide');
                $('#id-p-mp-alert').html(message);
                $('#id-box-mp-alert').modal('show');
            } else {
                window.location.reload();
            }

        }
    })
}

function confirmDate() {

    const txtInputDateTime = document.getElementById('datetimepicker');


    if (txtInputDateTime.value.trim() == '') {
        txtInputDateTime.classList.add('error-input');
        return;
    }



}

function acceptRequest() {
    $('#id-modal-date-picker').modal('show');
}

function removeErrorInput(e) {
    const eTarget = e.target;

    if (eTarget.classList.contains('error-input')) {
        eTarget.classList.remove('error-input');
    }
}





function rejectMerchandise(productCode, requestId, productName, requestedQty) {
    const title = document.getElementById('id-merchandise-title');
    const qty = document.getElementById('span-merchandise-qty');
    title.innerHTML = productName;
    qty.innerHTML = requestedQty;
    $('#id-reject-merchandise').modal('show');

    $('#id-reject-merchandise').on('shown.bs.modal', function () {
        let btnRejectAmount = document.getElementById('id-btn-reject-amount');
        let btnRejectAll = document.getElementById('id-btn-reject-all');

        btnRejectAll.setAttribute('data-productid', productCode);
        btnRejectAll.setAttribute('data-requestId', requestId);

        btnRejectAmount.setAttribute('data-productid', productCode);
        btnRejectAmount.setAttribute('data-requestId', requestId);
        btnRejectAmount.setAttribute('data-requestqty', requestedQty);
    });
}

function rejectByAmount(e) {
    let rejectAmtValue = txtRejectAmount.value.trim();
    let productId = e.target.dataset.productid;
    let requestId = e.target.dataset.requestid;
    let requestQty = e.target.dataset.requestqty;
    const rejectMsgBox = document.getElementById('id-reject-message-text');
    let rejectMsgBoxValue = rejectMsgBox.value.trim();



    if (rejectAmtValue != '' && rejectMsgBoxValue != '') {
        if (!isNaN(rejectAmtValue)) {
            rejectAmtValue = parseInt(rejectAmtValue);
            requestQty = parseInt(requestQty)

            if (rejectAmtValue > requestQty) {
                txtRejectAmount.classList.add('error-input');
            } else if (rejectAmtValue <= requestQty) {
                rejectMerchandiseQuantity(rejectAmtValue, productId, requestId, requestQty, rejectMsgBoxValue);
            }
           
        } else {
            txtRejectAmount.classList.add('error-input');
        }
    } else {
        if (rejectAmtValue == '') {
            txtRejectAmount.classList.add('error-input');
        }

        if (rejectMsgBoxValue == '') {
            rejectMsgBox.classList.add('error-input');
        }
    }
}

function rejectMerchandiseQuantity(rejectAmt, productId, requestId, requestQty, rejectMsg) {
    $.ajax({
        url: 'server/db_remove_requested_merchandise.php',
        type: 'POST',
        data: { rejectAmt: rejectAmt,
                productId: productId,
                requestId: requestId,
                requestQty: requestQty,
                rejectMsg: rejectMsg},
        dataType: 'json',
        success: (result) => {
            console.log(result);
            //window.location.reload();
        }
    })
}

function rejectAll(e) {
    let productId = e.target.dataset.productid;
    let requestId = e.target.dataset.requestid;

    removeMerchandise(productId, requestId);
}

// function removeMerchandise(productId, requestId) {
//     if (tempReject.trim() != '') {
//         $.ajax({
//             url: 'server/db_remove_requested_merchandise.php',
//             type: 'POST',
//             data: { removeItemId: productId,
//                     requestId: requestId,
//                     rejectReason: tempReject},
            
//             success: (result) => {
//                 console.log(result);
//             }
//         });
//     }
// }

function showComment(productId, requestId) {
    
    $.ajax({
        url: 'server/db_get_mer_history.php',
        type: 'POST',
        data: { productId: productId,
                requestId: requestId},
        success: (result) => {
            let bodyModal = document.getElementById('id-modal-body-comment');
            bodyModal.innerHTML = result;
            $('#id-modal-comment').modal('show');
            
        }
    })
}

function addProduct(evt, productId, requestId, operationType) {
    const targetGrandparent = evt.target.parentElement.parentElement.parentElement.parentElement;
    const productName = targetGrandparent.querySelector('.td-product-name').innerHTML;
    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    const urlRequestId = urlParams.get('request_id')
    const btnMerchandise = document.getElementById('id-btn-merchandise');
    const btnRemoveMerchandise = document.getElementById('id-btn-remove-merchandise');
    
    if (urlRequestId != requestId) {
        window.location.href = 'admin_request.php';
    }

    switch (operationType) {
        case '1':
            $('#id-merchandise-title').html(productName);
            $('#id-p-merchandise').html('Add amount');
            btnMerchandise.innerHTML = 'Add';
            btnMerchandise.setAttribute('data-product', productId);
            btnMerchandise.setAttribute('data-request', requestId);
            btnMerchandise.setAttribute('data-operation', 1);
            $('#id-add-amount').modal('show');
            break;
        case '2':
            $('#id-merchandise-title').html(productName);
            $('#id-p-merchandise').html('Edit amount');
            btnMerchandise.innerHTML = 'Edit';
            btnMerchandise.setAttribute('data-product', productId);
            btnMerchandise.setAttribute('data-request', requestId);
            btnMerchandise.setAttribute('data-operation', 2);
            $('#id-add-amount').modal('show');
            break;
        case '3':
            btnRemoveMerchandise.setAttribute('data-product', productId);
            btnRemoveMerchandise.setAttribute('data-request', requestId);
            btnRemoveMerchandise.setAttribute('data-operation', 3);
            $('#id-modal-remove-confirmation').modal('show');

            break;
        default:
            return;
            break;
    }
}

function removeMerchandise(evt) {
    let productId = evt.target.dataset.product;
    let requestId = evt.target.dataset.request;
    let operationType = evt.target.dataset.operation;
    console.log()
    merchandiseOperation(productId, requestId, operationType, null);
}
