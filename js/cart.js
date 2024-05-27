let tbody;
let btnDeleteData;
let btnConfirm;

window.onload = () => {
    tbody = document.querySelector('.table-body');
    btnDeleteData = document.getElementById('id-btn-remove');
    btnConfirm = document.getElementById('id-btn-confirm');
    let btnPopup = document.querySelector('.close-popup');
    
    btnPopup.addEventListener('click', () => {
        $('.modal-remove-from-cart').modal('hide');
    })

    $('.modal-remove-from-cart').on('hidden.bs.modal', function (e) {
        btnDeleteData.removeAttribute('data-code');
    })

    loadPage()
}


// Load page
function loadPage() {
    return $.ajax({
        url: 'server/display_cart.php',
        type: 'POST',
        data: {data: 'data',
                cart: 'cart'},
        success: (data) => {
            tbody.innerHTML = data;
            let cartQuantity = document.querySelector('.cart-quantity').innerHTML;
            cartQuantity = parseInt(cartQuantity);
            if (cartQuantity != 0) {
                btnConfirm.disabled = false;
            } else {
                btnConfirm.disabled = true;
            }
        }
    });
}

// Add Quantity Button 
function addItemQuantity(e, operator) {
    let code = e.target.dataset.id;
    let locationId = e.target.dataset.location;

    modifyQuantity(code, null, operator, locationId, e);
} 

// Add or reduce product quantity
function modifyQuantity(code, quantity, operator, locationId, e) {
    $.ajax({
        url: 'server/db_add_quantity_cart.php',
        type: 'POST',
        data: { code: code,
                operator: operator,
                enterQuan: quantity,
                locationId: locationId},
        success: (result) => {

            //console.log(result);
            if (result == 400) {
                let errorDiv = e.target.parentElement.parentElement.childNodes[7];
                errorDiv.style.display = 'block';
                errorDiv.style.position = 'absolute';
                errorDiv.style.left = '0';
                errorDiv.style.right = '0';
            } else if (result == 300) {
                displayDeleteConfirmation(code, locationId);
            } else if(result == 200) {
                loadPage();
            }
            
        }
    });
}

function showDelete(code, locationId) {
    displayDeleteConfirmation(code, locationId);
}

function displayDeleteConfirmation(code, locationId) {
    btnDeleteData.setAttribute('data-code', code);
    btnDeleteData.setAttribute('data-locationid', locationId);
    $('.modal-remove-from-cart').modal('show');
}

// Complete remove item SQL command and refresh the page
function removeItem(e) {
    let code = e.target.dataset.code;
    let locationId = e.target.dataset.locationid;

    $.ajax({
        url: 'server/db_remove_from_cart.php',
        type: 'POST',
        data: { code: code,
                locationId: locationId},
        success: (result) => {
            console.log(result);
            $('.modal-remove-from-cart').modal('hide');
            loadPage();
        }
    })
}


// To display removed item due to out of stock
function displayRemovedItem(itemList) {
    let modal = document.querySelector('.modal-display-removed-item .modal-body');

    itemList.forEach(item => {
        let p = document.createElement('p');
        p.classList.add('list');
        p.innerHTML = item;
        modal.appendChild(p);
    })
    $('.modal-display-removed-item').modal('show');
}


function validateFocus(evt, code, locationId) {
    let enterQuan = evt.target.value;
    modifyQuantity(code, enterQuan, 2, locationId, evt);
}

function confirmCart(evt) {
    let totalCart = evt.target.dataset.totalCart;
 
    if (totalCart != 0) {
        window.location.href = "request.php";
    }
}