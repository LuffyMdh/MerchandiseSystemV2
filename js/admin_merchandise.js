const url = new URL(window.location.href);
let eventListener = true;
let txtProductQuantity; 
let imgProductUpload; 
let btnDeleteMerch
let btnAdd;
let allCheckbox; 
let checkBoxes;
let checkboxesCodeArray = [];
let totalProduct;
let totalCheck = 0;
let btnCancelAddStock;
let alertNoti;
let alertMsg;
let drpdownView;

let currentRemoveCategoryActiveTab = '';

function getCurrentURLCode(URLCode) {
    const urlRequestId = window.location.search;
    const urlParams = new URLSearchParams(urlRequestId);
    let statusCode = urlParams.get(URLCode);
    return statusCode;
}

// async function getState() {
//     const statesData = [];
//     const data = await fetchData();
//     statesData.push(...data);
//     console.log(data);
// }

window.onload = () => {
    const allCategory = document.querySelectorAll('.search-filter .dropdown-menu a');
    let currentActiveTab = allCategory[0];
    let search = null;
    let cateCode = 'all';

    // Start Add Category/Group/Location Box
    const btnListAdd = document.querySelectorAll('.add-div-list ul li');
    const addCategoryTitle = document.getElementById('id-add-category-title');
    const btnCategory = document.getElementById('id-btn-add-category');
    const txtCategoryName = document.getElementById('id-txt-add-category-name');
    // End Add Category/Group/Location Box

    // Start Remove Category/Group/Location Box
    const btnListRemove = document.querySelectorAll('.remove-div-list ul li');
    const removeCategoryTitle = document.getElementById('id-remove-category-title');
    const btnRemoveCategoryDropdown = document.getElementById('id-remove-category-btn-dropdown');
    const removeCategoryBox = document.getElementById('id-remove-category-box');
    const removeCategoryBoxUL = document.getElementById('id-remove-category-ul');
    const btnRemoveCategory = document.getElementById('id-btn-remove-category');
    const btnConfirmRemoveCategory = document.getElementById('id-btn-confirm-delete-category');
    // End Remove Category/Group/Location Box


    // Add Stock Box
    const txtAddStock = document.getElementById('id-txtAddStock-input');
    const btnAddStock = document.getElementById('id-addStock-add');
    // End Add Stock Box

    const txtSearch = document.querySelector('.search-bar');
    const btnSearch = document.querySelector('.search_icon')
    const searchForm = document.getElementById('id-searchForm');
    const btnClose = document.getElementById('id-btn-view-close');
    const btnEdit = document.getElementById('id-btn-view-edit-save');
    
    const btnNewMerchandise = document.getElementById('id-add-new-merchandise');
    btnDeleteMerch = document.getElementById('id-remove-merchandise');

    const btnCloseNoti = document.getElementById('id-close-noti');

    // Start Add Box Section
    //Category Dropdown Items
    const allDropdown = document.querySelectorAll('.btn-dropdown');
    const dropdownAddCategory = document.querySelectorAll('.add-dropdown-category');
    const dropdownAddGroup = document.querySelectorAll('.add-dropdown-group');

    const btnAddCategory = document.getElementById('id-add-dropdown-category');
    const btnAddGroup = document.getElementById('id-add-dropdown-group');

    const txtInputAddName = document.getElementById('id-txt-add-name');
    const txtInputAddDesc = document.getElementById('id-txt-add-desc');
    const txtInputAddQuantity = document.getElementById('id-txt-add-quantity');

    const imgInputAddUpload = document.getElementById('id-img-add-upload');

    const btnAddNewMerchandise = document.getElementById('id-btn-add-add');
    // End Add Box Section

    // Start Multipurpose Alert Popup Box
    const pMpAlert = document.getElementById('id-p-mp-alert');
    // End Multipurpose Alert Popup Box

    // Start Return Product
    const btnReturnProductLocation = document.getElementById('id-btn-return-product');
    const dropdownReturnProductItems = document.getElementById('id-div-return-product');
    const btnReturnProductConfirm = document.getElementById('id-btn-confirm-return-product');
    btnReturnProductLocation.disabled = true;
    //End Return Product

    // Start Pagination
    const btnNextPg = document.getElementById('id-pagination-next');
    const currentPgHTML = document.getElementById('id-pagination-current');
    const btnPrevPg = document.getElementById('id-pagination-previous');
    let currentPg = 1;
    let offset = 0;
    // End Pagination


    const btnConfirmDelete = document.getElementById('id-btn-delete-merchandise-confirm');

    btnAdd = document.getElementById('id-btn-add-new');
    const txtArray = document.querySelectorAll('.modal-dialog .txtInput');
    const dropdownArray = document.querySelectorAll('.modal-dialog .btn-dropdown');
    allCheckbox = document.getElementById('id-top-checkbox');
    
    btnCancelAddStock = document.getElementById('id-addStock-cancel');
    
    alertNoti = document.getElementById('myAlert');
    alertMsg = document.querySelector('.alert-message');
    drpdownView = document.querySelectorAll('.btn-dropdown-view');
    let btnName = document.querySelector('.search-filter button');



    // Remove Merchandise Button


    btnAdd.style.display = 'none';
    btnEdit.style.display = 'none';

    // Start Pagination Procedure
    disabledBtn(btnNextPg);
    disabledBtn(btnPrevPg);
    
    btnNextPg.addEventListener('click', () => {
        currentPg++;
        currentPgHTML.innerHTML = currentPg;
        offset += 10;
        enabledBtn(btnPrevPg);
        console.log(offset);
        displayProduct(cateCode, search, offset);
    })

    btnPrevPg.addEventListener('click', () => {
        currentPg--;
        currentPgHTML.innerHTML = currentPg;
        offset -= 10;
        currentPg == 1 ? disabledBtn(btnPrevPg) : enabledBtn(btnPrevPg);
        displayProduct(cateCode, search, offset);
    })

    // End Pagination Procedure


    displayProduct(cateCode, search, offset);

    allCategory.forEach(category => {
        category.addEventListener('click', (e) => {
            offset = 0;
            currentPg = 1;
            currentPgHTML.innerHTML = 1;
            disabledBtn(btnPrevPg);
            search = null;

            cateCode = e.target.dataset.code;

            if (e.target.innerHTML == 'All') {
                btnName.innerHTML = 'Category';
                cateCode = 'all';
            } else {
                btnName.innerHTML = e.target.innerHTML;
            }

            currentActiveTab.classList.remove('dropdown-active');
            currentActiveTab = e.target;
            e.target.classList.add('dropdown-active');
            
            url.searchParams.set('categoryCode', cateCode);
            window.history.replaceState(null, null, url);

            displayProduct(cateCode, null, offset)
            
        })
    })

    // Start Add Merchandise/Category/Group/Location Popup
    btnListAdd.forEach(btn => {
        btn.addEventListener('click',(e) => {
            const addType = e.target.dataset.id;

            switch(addType) {
                case '1': // Add New Merchandise
                    btnAddCategory.setAttribute('data-code', '0');
                    btnAddGroup.setAttribute('data-code', '0');
                    $('#id-add-product-box').modal('show');
                    break;
                case '2': // Add New Category
                    addCategoryTitle.innerHTML = 'Add New Category';
                    txtCategoryName.setAttribute('placeholder', 'Enter category name');
                    btnCategory.setAttribute('data-code', addType);
                    $('#id-add-category-box').modal('show');
                    break;

                case '3': // Add New Group
                    addCategoryTitle.innerHTML = 'Add New Group';
                    txtCategoryName.setAttribute('placeholder', 'Enter group name');
                    btnCategory.setAttribute('data-code', addType);
                    $('#id-add-category-box').modal('show');
                    break;

                case '4': // Add New Location
                    addCategoryTitle.innerHTML = 'Add New Location';
                    txtCategoryName.setAttribute('placeholder', 'Enter location name');
                    btnCategory.setAttribute('data-code', addType);
                    $('#id-add-category-box').modal('show');
                    break;

                default:
                    return;
            }
            
        })
    })

    btnCategory.addEventListener('click', (e) => {
        let addCode = e.target.dataset.code;
        let txtCategoryNameValue = txtCategoryName.value.trim();

        if (txtCategoryNameValue == '') {
            txtCategoryName.classList.add('error-input');
            return;
        }
        
        $.ajax({
            url: 'server/db_add_CGL.php',
            type: 'POST',
            data: { addCode: addCode,
                    name: txtCategoryNameValue},
            dataType: 'json',
            success: (result) => {
                console.log(result);
                let returnCode = result.returnCode;
                console.log(returnCode);
                if (returnCode == 200) {
                    $('#id-add-category-box').modal('hide');

                    switch(addCode) {
                        case '2':
                            $('#id-p-success').html('New category successfully created!');
                            break;
                        case '3':
                            $('#id-p-success').html('New group has been successfully added!');
                            break;
                        case '4':
                            $('#id-p-success').html('New location successfully added to the system!');
                            break;
                    }

                    
                    $('#id-request-done').modal('show');
                }
            }
        });
    
    })

    $('#id-add-category-box').on('hidden.bs.modal', () => {
        txtCategoryName.value = '';
        btnCategory.removeAttribute('data-code')
        txtCategoryName.removeAttribute('placeholder');
        txtCategoryName.classList.remove('error-input');
    })


    // Start Remove Category/Group/Location Popup
    btnListRemove.forEach(btn => {
        btn.addEventListener('click', (e) => {
            const removeType = e.target.dataset.id;

            switch (removeType) {
                case '1':
                    removeCategoryTitle.innerHTML = 'Remove Category';
                    btnRemoveCategoryDropdown.innerHTML = 'Choose category:';
                    $('#id-p-confirm-remove-category').html('Confirm remove category?');
                    getCGL(removeCategoryBox, removeCategoryBoxUL, removeType);
                    $('#id-remove-category-box').modal('show');
                    break;

                case '2':
                    removeCategoryTitle.innerHTML = 'Remove Group';
                    btnRemoveCategoryDropdown.innerHTML = 'Choose group:';
                    $('#id-p-confirm-remove-category').html('Confirm remove group?');
                    getCGL(removeCategoryBox, removeCategoryBoxUL, removeType);
                    $('#id-remove-category-box').modal('show');
                    break;

                case '3':
                    removeCategoryTitle.innerHTML = 'Remove Location';
                    btnRemoveCategoryDropdown.innerHTML = 'Choose location:';
                    $('#id-p-confirm-remove-category').html('Confirm remove location?');
                    getCGL(removeCategoryBox, removeCategoryBoxUL, removeType);
                    $('#id-remove-category-box').modal('show');
                    break;

                default:
                    return;
            }

            btnConfirmRemoveCategory.setAttribute('data-type', removeType);
        })
    })

    btnRemoveCategory.addEventListener('click', (e) => {
        $('#id-remove-category-box').modal('hide');
        $('#id-box-confirm-delete-category').modal('show');


    })

    btnConfirmRemoveCategory.addEventListener('click', (e) => {
        const removeType = e.target.dataset.type;
        const removeCode = e.target.dataset.code;

        $.ajax({
            url: 'server/db_remove_CGL.php',
            type: 'POST',
            data: { removeType: removeType,
                    removeCode: removeCode
            },
            dataType: 'json',
            success: (result) => {
                console.log(result);

                let statusCode = result.returnCode;
                if (statusCode == 200) {
                    console.log('check');
                    pMpAlert.innerHTML = result.message;
                    $('#id-box-confirm-delete-category').modal('hide');
                    $('#id-box-mp-alert').modal('show');
                    
                } else if (statusCode == 405) {
                    console.log(result.message);
                    pMpAlert.innerHTML = result.message;
                    $('#id-box-confirm-delete-category').modal('hide');
                    $('#id-box-mp-alert').modal('show');
                }
            }
        });
    })

    $('#id-remove-category-box').on('hidden.bs.modal', () => {
        btnRemoveCategory.disabled = true;
    })

    $('#id-box-confirm-delete-category').on('hidden.bs.modal', () => {
        btnConfirmRemoveCategory.removeAttribute('data-type');
        btnConfirmRemoveCategory.removeAttribute('data-code');
    })
    // End Remove Category/Group/Location Popup

    // Button close view popup
    btnClose.addEventListener('click', () => {
        $('#id-viewEditBox').modal('hide');
    })

    dropdownAddCategory.forEach(dropdown => {
        dropdown.addEventListener('click', (e) => {
            let code = e.target.dataset.code;
            
            btnAddCategory.innerHTML = e.target.innerHTML;
            btnAddCategory.setAttribute('data-code', code);

            if (btnAddCategory.classList.contains('error-input')) {
                btnAddCategory.classList.remove('error-input');
            }

        })
    })

    dropdownAddGroup.forEach(dropdown => {
        dropdown.addEventListener('click', (e) => {
            let code = e.target.dataset.code;

            btnAddGroup.innerHTML = e.target.innerHTML;
            btnAddGroup.setAttribute('data-code', code);

            if (btnAddGroup.classList.contains('error-input')) {
                btnAddGroup.classList.remove('error-input');
            }
        })
    })

    allDropdown.forEach(dropdown => {

        dropdown.addEventListener('onchange', (e) => {
            if (dropdown.classList.contains('error-input')) {
                dropdown.classList.remove('error-input');
            }
        })
    })

    $('#id-add-product-box').on('hidden.bs.modal', () => {
        btnAddCategory.innerHTML = 'Select category: ';
        btnAddGroup.innerHTML = 'Select group: ';
        btnAddCategory.setAttribute('data-code', '0');
        btnAddGroup.setAttribute('data-code', '0');



        txtInputAddName.value = '';
        txtInputAddDesc.value = '';

        txtInputAddName.classList.remove('error-input');
        txtInputAddQuantity.classList.remove('error-input');
        txtInputAddDesc.classList.remove('error-input');

        btnAddCategory.classList.remove('error-input');
        btnAddGroup.classList.remove('error-input');

        imgInputAddUpload.value ='';
    })

    btnAddNewMerchandise.addEventListener('click', () => {
        let txtNameValue = txtInputAddName.value;
        let txtDescValue = txtInputAddDesc.value;
        let txtQuantityValue = txtInputAddQuantity.value;
        let categoryCode = btnAddCategory.dataset.code;
        let groupCode = btnAddGroup.dataset.code;
        
        let addBoolean = true;

        if (txtNameValue.trim() == '') {
            txtInputAddName.classList.add('error-input');
            addBoolean = false;
        }

        if (txtDescValue.trim() == '') {
            txtInputAddDesc.classList.add('error-input');
            addBoolean = false;
        }

        if (txtQuantityValue.trim() == '') {
            txtInputAddQuantity.classList.add('error-input');
        }

        if (categoryCode == 0) {
            btnAddCategory.classList.add('error-input');
            addBoolean = false;
        }

        if (groupCode == 0) {
            btnAddGroup.classList.add('error-input');
            addBoolean = false;
        }

        if (addBoolean) {
            addNewMerchandise(txtNameValue, txtDescValue, txtQuantityValue, categoryCode, groupCode, imgInputAddUpload.files[0])
        }
     })

    // End Add Merchandise Popup

    // Remove error textbox message
    txtArray.forEach(txt => {
        if (txt.type == 'text' || txt.type == 'textarea') {
            txt.addEventListener('keypress', (e) => {
                e.target.classList.remove('error-input');
            })
        }
    });


    allCheckbox.addEventListener('change', (e) => {
        let state = e.target.checked;
        let code = e.target.dataset.code;
        checkBoxes.forEach(checkbox => {
            if (state) {
                if (checkbox.checked) {
                    checkbox.checked = state;
                } else {
                    checkbox.checked = state;
                    checkbox.onchange(e, code);
                }
            } else {
                checkbox.checked = state;
                checkbox.onchange(e, code);
            }
        })
    })

    // Button remove merchandise
    btnDeleteMerch.addEventListener('click', (e) => {
        $('#id-box-confirm-delete').modal('show');
        // 
    })

    btnConfirmDelete.addEventListener('click', (e) => {
        removeMerchandise();
    })


    btnCancelAddStock.addEventListener('click', (e) => {
        $('#id-addStockBox').modal('hide');
    })

    $('#id-addStockBox').on('hidden.bs.modal', (e) => {
        txtAddStock.classList.remove('error-input');
        txtAddStock.value = '';
    })

    btnCloseNoti.addEventListener('click', () => {
        alertNoti.style.display = 'none';
    })

    btnSearch.addEventListener('click', (e) => {
        offset = 0;
        currentPg = 1;
        currentPgHTML.innerHTML = currentPg
        btnName.innerHTML = 'Category';
        cateCode = 'all';
        currentActiveTab = allCategory[0];

        disabledBtn(btnPrevPg);

        e.preventDefault();
        search = txtSearch.value.trim();

        if (search != '') {
            btnName.innerHTML = 'Category';
            displayProduct('all', search, offset);
            txtSearch.value = '';

            allCategory.forEach(category => {
                category.classList.remove('dropdown-active');
            })
            currentActiveTab = allCategory[0];
            allCategory[0].classList.add('dropdown-active');
        }
        txtSearch.value = '';
    })


    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        offset = 0;
        currentPg = 1;
        currentPgHTML.innerHTML = currentPg
        disabledBtn(btnPrevPg);
        btnName.innerHTML = 'Category';
        cateCode = 'all';
        currentActiveTab = allCategory[0];


        search = txtSearch.value.trim();

        if (search != '') {
            displayProduct('all', search, offset);
        } else {
            displayProduct('all', null, offset);
        }

        txtSearch.value = '';
    })

    $('#id-addStockBox').on('hidden.bs.modal', () => {
        const addStockDropDownItems = document.querySelectorAll('.div-add-stock a');
        addStockDropDownItems.forEach(item => {
            item.removeEventListener('click', handler);
        })
    })

    $('#id-box-return-product').on('hidden.bs.modal', () => {
        dropdownReturnProductItems.innerHTML = '';
    })

    $('#id-box-confirm-return-product').on('hidden.bs.modal', () => {
        btnReturnProductConfirm.removeAttribute('data-code');
        btnReturnProductConfirm.removeAttribute('data-product');
    })

    $('#id-request-done').on('hidden.bs.modal', () => {
        window.location.reload();
    })

    btnReturnProductConfirm.addEventListener('click', (e) => {
        let productId = e.target.dataset.product;
        let locationId = e.target.dataset.code;

        $.ajax({
            url: 'server/db_admin_return_product.php',
            type: 'POST',
            data: { productId: productId,
                    locationId: locationId
            },
            dataType: 'json',
            success: (result) => {
                let returnCode = result.returnCode;
                
                if (returnCode == 200) {
                    $('#id-box-confirm-return-product').modal('hide');
                    $('#id-p-mp-alert').html(result.message);
                    $('#id-box-mp-alert').modal('show');
                }
            }
        })
    })

    btnReturnProductLocation.addEventListener('click', () => {
        $('#id-box-return-product').modal('hide');
        $('#id-box-confirm-return-product').modal('show');
    })

    $('#id-box-mp-alert').on('hidden.bs.modal', () => {
        window.location.reload();
    })



}

// End Window Load

function getCGL(removeCategoryBox, removeCategoryBoxUL, removeType) {
    $.ajax({
        url: 'server/db_get_CGL.php',
        type: 'POST',
        data: {removeType: removeType},
        dataType: 'json',
        success: (result) => {
            let returnCode = result.returnCode;
            // console.log(result);
            removeCategoryBoxUL.innerHTML = '';

            if (returnCode == 200) {
                result.list.forEach(item => {
                    const a = document.createElement('a');
                    a.className = 'dropdown-item';
                    

                    a.innerHTML = Object.values(item)[0];
                    a.setAttribute('data-code',Object.keys(item)[0] )
                    a.setAttribute('onclick', 'removeCategory(event)');
                    if (removeType == 3 && Object.values(item)[0] == 'Kuching') {
                        a.classList.add('disabled');
                        a.style.pointerEvents = 'none';
                        a.style.cursor = 'not-allowed';
                    }

                    removeCategoryBoxUL.appendChild(a);
                    


                })
            } else if (returnCode == 400) {
                p = document.createElement('p');
                p.innerHTML = result.list;
                p.style.textAlign = 'center';
                p.style.margin = '10px';
                p.style.cursor = 'auto';
                removeCategoryBoxUL.appendChild(p);
            } else if (returnCode == 500) {

            }

        }
    })
}
// STOP HERE ACTIVE buTTON HUHU


function removeCategory(e) {
    const dropdownBtnCategory = document.getElementById('id-remove-category-btn-dropdown');
    const btnRemoveCategory = document.getElementById('id-btn-remove-category');
    const btnConfirmRemoveCateogry = document.getElementById('id-btn-confirm-delete-category');

    dropdownBtnCategory.innerHTML = e.target.innerHTML;
    if (currentRemoveCategoryActiveTab !== '') {
        currentRemoveCategoryActiveTab.classList.remove('dropdown-active');
    }

    e.target.classList.add('dropdown-active');
    currentRemoveCategoryActiveTab = e.target;
    btnRemoveCategory.disabled = false;

    btnConfirmRemoveCateogry.setAttribute('data-code', e.target.dataset.code);
    
}

function modifyURL() {
    url.searchParams.delete('productCode');
    window.history.replaceState(null, null, url);
}

function displayProduct(cate, searchTxt, offset) {
    const btnNextPg = document.getElementById('id-pagination-next');

    try {
        $.ajax({
            url: 'server/db_admin_display_product.php',
            type: 'POST',
            data: { cate: cate,
                    searchTxt: searchTxt,
                    offset: offset},
            dataType: 'json',
            success: (result) => {
                let tbody = document.querySelector('.table tbody');
                tbody.innerHTML = result.html;

                checkBoxes = document.querySelectorAll('.table .product-checkbox')
                
                resultTotalRecord = result.totalRecord;
                totalProduct = resultTotalRecord == 11 ? 10 : resultTotalRecord;
                resultTotalRecord == 11 ? enabledBtn(btnNextPg) : disabledBtn(btnNextPg);

                checkboxesCodeArray = [];
                allCheckbox.checked = false;
                btnDeleteMerch.style.pointerEvents = 'none';
                btnDeleteMerch.disabled = true;
            }
        })
    } catch (err) {
        console.log(err);
    }
}


function formValidation(category) {
    let boolean = true;

    txtArray.forEach(txt => {
        if (txt.type == 'text' || txt.type == 'textarea') {
            if (txt.value.trim() == '') {
                txt.classList.add('error-input');
                boolean = false;
            }
        }
    })

    if (drpProductCategory.dataset.code == undefined) {
        drpProductCategory.classList.add('error-input');
        boolean = false;
    }

    return boolean;
}
// addNewMerchandise(txtNameValue, txtDescValue, categoryCode, groupCode, imgInputAddUpload.files[0])
function addNewMerchandise(name, desc, quantity, cate, group, img) {
    const addData = new FormData();
    addData.append('name', name);
    addData.append('group', group);
    addData.append('quantity', quantity)
    addData.append('category', cate);
    addData.append('desc', desc);
    addData.append('img', img);

    $.ajax({
        url: 'server/db_add_new_merchandise.php',
        type: 'POST',
        data: addData,
        contentType: false, 
        processData: false,
        dataType: 'json',
        success: (result) => {
            let returnCode = result.returnCode;

            if (returnCode == 200) {
                $('#id-add-product-box').modal('hide');
                $('#id-p-success').html('Success! Your New Merchandise Has Been Added to the Collection!');
                $('#id-request-done').modal('show');
            }
            console.log(result);
        }
    });
}


function addToCheckbox(e, code) {
    let checkboxState = e.target.checked;

    if (checkboxState) {
        checkboxesCodeArray[code] = e.target.dataset;
        
        totalCheck++;
        if (totalCheck == totalProduct) {
            allCheckbox.checked = checkboxState;
        }

    } else {
        delete checkboxesCodeArray[code];
        allCheckbox.checked = checkboxState;
        totalCheck--;
    }

    if (totalCheck != 0) {
        btnDeleteMerch.style.pointerEvents = 'auto';
        btnDeleteMerch.classList.remove('disabled');
    } else {
        btnDeleteMerch.style.pointerEvents = 'none';
        btnDeleteMerch.classList.add('disabled');
    }
}



function removeMerchandise() {
    const checkBoxesArray = [];

    for (const key in checkboxesCodeArray) {
        checkBoxesArray.push(key);
    }

    $.ajax({
        url: 'server/db_admin_remove_product.php',
        type: 'POST',
        data: {data: checkBoxesArray},
        success: (result) => {
            
            $('#id-box-confirm-delete').modal('hide');

            $('#id-p-mp-alert').html('Merchandise removed!');

            $('#id-box-mp-alert').addClass('removeMerchandise');
            $('#id-box-mp-alert').modal('show');

            $('.removeMerchandise').on('hidden.bs.modal', () => {
                location.reload();
            });
        }
    })
}

// Below these are the new codes . Freshly brewed . More readable ? (Cant understand , can ask Lut . Purely 100% By Lut P.S No Chatgpt :D) 

let varHandle;
let addStockActiveDropdown = '';

function handler(e) {
    varHandle(e);
}

function displayAddStock(e, code, name) {
    console.log('check');
    $.ajax({
        url: 'server/db_get_product_quantity.php',
        type: 'POST',
        data: {code:code},
        dataType: 'json',
        success: (result) => {
            let statusCode = result.statusCode;
            console.log(result);
            if (statusCode === 200) {
                const headTitle = document.getElementById('id-addStockTitle');
                const btnAddDropDown = document.getElementById('id-add-stock-dropdown-location');
                const addStockDropDownItems = document.querySelectorAll('.div-add-stock a');
                const txtInputAddStock = document.getElementById('id-txtAddStock-input');
                const pCurrentQuantity = document.getElementById('id-current-quantity');
                const btnAddStock = document.getElementById('id-addStock-add');
                const spanAddStockWarning = document.getElementById('id-span-addstock-inactive-warning');
                spanAddStockWarning.style.display = 'none';
                addStockActiveDropdown = '';
                btnAddDropDown.value = 0;

                headTitle.innerHTML = name + " - Add Stock";
                btnAddDropDown.innerHTML = 'Select location: ';
                pCurrentQuantity.innerHTML = '';
                
                if (btnAddDropDown.classList.contains('error-input') || txtInputAddStock.classList.contains('error-input')) {
                    btnAddDropDown.classList.remove('error-input');
                    txtInputAddStock.classList.remove('error-input');
                }

                dropdownState(btnAddDropDown, true);
                txtInputState(txtInputAddStock, false);

                varHandle = function handle(evt) {
                    if (addStockActiveDropdown !== '') { // If dropdown-active is assigned then
                        addStockActiveDropdown.classList.remove('dropdown-active');
                    }
                                                                                         
                    

                    addStockActiveDropdown = evt.target;
                    evt.target.classList.add('dropdown-active');
                    btnAddDropDown.innerHTML = evt.target.innerHTML;
                    let locationId = evt.target.dataset.code;
                    btnAddDropDown.value = locationId;
                    
                    if (locationId != 0) {
                        if (locationId != 1) {
                            headTitle.innerHTML = name + " - Transfer Stock From Kuching To:";
                        } else {
                            headTitle.innerHTML = name + " - Add Stock";
                        }

                        let productStatus = result[locationId][1];
                        let productQuantity = result[locationId][0];
                        
                        if (btnAddDropDown.classList.contains('error-input')) {
                            btnAddDropDown.classList.remove('error-input');
                        }

                        if (locationId != 1 && productStatus == 0) {
                            txtInputState(txtInputAddStock, false);
                            spanAddStockWarning.style.display = 'inline';
                        } else {
                            txtInputState(txtInputAddStock, true);
                            spanAddStockWarning.style.display = 'none';

                        }
                        pCurrentQuantity.innerHTML = productQuantity;
                        btnAddStock.setAttribute('data-code', code);
                        btnAddStock.setAttribute('data-location', locationId);
                        btnAddStock.setAttribute('data-locationname', btnAddDropDown.innerHTML);
                    } else {
                        txtInputState(txtInputAddStock, false);
                    }


                }

                addStockDropDownItems.forEach(item => {
                    item.classList.remove('dropdown-active');
                    item.addEventListener('click',  handler, false);
                })


                $('#id-addStockBox').modal('show');
            } else if (result.statusCode = 400) {
                console.log(result.message);
            }
        }
    })


}

function addStock(e) {
    const txtInputAddStock = document.getElementById('id-txtAddStock-input');
    const btnAddDropDown = document.getElementById('id-add-stock-dropdown-location');
    const productId = e.target.dataset.code;
    let btnValue = btnAddDropDown.value;
    let quantityValue = txtInputAddStock.value.trim();
    


    if (btnValue == 0) {
        btnAddDropDown.classList.add('error-input');
    } else {
        if (quantityValue == '') {
            txtInputAddStock.classList.add('error-input');
         } else {
            let intQuantityValue = parseInt(quantityValue);
            if (isNaN(intQuantityValue)) {
                txtInputAddStock.classList.add('error-input');
            } else {
                let locationId = e.target.dataset.location;
                let locationName = e.target.dataset.locationname;
                if (locationId != 1) {
                    let btnConfirmBox = document.getElementById('id-btn-open-confirm');
                    let pConfirmChange = document.getElementById('id-p-confirm-change');
                    let btnAddStockConfirm = document.getElementById('id-addStock-add-confirm');

                    btnAddStockConfirm.setAttribute('data-code', productId);
                    btnAddStockConfirm.setAttribute('data-location', locationId);
                    btnAddStockConfirm.setAttribute('data-quantity', intQuantityValue);
                    btnAddStockConfirm.setAttribute('data-locationname', locationName);

                    pConfirmChange.innerHTML = 'Transfer Merchandise From Kuching To ' + locationName;
                   
                    btnConfirmBox.click();
                } else {
                    updateProductQuantity(productId, intQuantityValue, locationId, locationName);
                }
            }
      
         }
    }
}

function addConfirmStock(e) {
    let locationId = e.target.dataset.location;
    let productId = e.target.dataset.code;
    let quantity = e.target.dataset.quantity;
    let locationName = e.target.dataset.locationname;

    updateProductQuantity(productId, quantity, locationId, locationName);
}


function updateProductQuantity(code, addQuantity, locationId, locationName) {
    const pMpAlert = document.getElementById('id-p-mp-alert');
    $.ajax({
        url: 'server/db_add_stock.php',
        type: 'POST',
        data: { code: code,
                quan: addQuantity,
                locationId: locationId,
                locationName: locationName},
        dataType: 'json',
        success: (result) => {
            console.log(result);
            let statusCode = result.statusCode;

            if (statusCode == 500) { // DB connection problem
                console.log(result.message);
            } else if (statusCode == 401) { // Quantity tidak mencukupi
                pMpAlert.innerHTML = result.message;
                $('#id-box-confirm-transfer').modal('hide');
                $('#id-box-mp-alert').modal('show');
            } else if (statusCode == 200) {
                pMpAlert.innerHTML = result.message;
                $('#id-addStockBox').modal('hide');
                $('#id-box-confirm-transfer').modal('hide');
                $('#id-box-mp-alert').modal('show');
            }
        }
    })
}

// Start View  Popup

let functionHandler;
let locationHandler;
let categoryHandler;
let groupHandler;
let setStatusHandler;

function handle(e) {
    functionHandler(e);
}

function locationHandle(e) {
    locationHandler(e);
}

function categoryHandle(e) {
    categoryHandler(e);
}

function groupHandle(e) {
    groupHandler(e);
}

function setStatusHandle(e) {
    setStatusHandler(e);
}

function viewProduct(evt, code) {
    const txtTitle = document.getElementById('id-viewEditBox-title');
    const txtInputName = document.getElementById('id-txt-view-name');
    const txtInputDesc = document.getElementById('id-txt-view-desc');
    const imgInputUpload = document.getElementById('id-img-upload');
    const imgInputCurrent = document.getElementById('id-view-img');
    const chkBoxActive = document.getElementById('id-view-input-slider');

    const viewDropdown = document.querySelectorAll('.viewEditBox .btn-dropdown');
    const txtInput = document.querySelectorAll('.viewEditBox .txtInput');
    const txtInputEnable = document.querySelectorAll('.viewEditBox .txtInputEnable')

    const txtInputQuantity = document.getElementById('id-txt-view-quantity');

    const btnLocation = document.getElementById('id-view-dropdown-location');
    const btnCategory = document.getElementById('id-view-dropdown-category');
    const btnGroup = document.getElementById('id-view-dropdown-group');

    const btnClose = document.getElementById('id-btn-view-close');
    const btnEdit = document.getElementById('id-btn-view-edit-save');

    const dropdownLocation = document.querySelectorAll('.viewEditBox .dropdown-location');
    const dropdownCategory = document.querySelectorAll('.viewEditBox .dropdown-category');
    const dropdownGroup = document.querySelectorAll('.viewEditBox .dropdown-group');

    const spanQuantityWarning = document.getElementById('id-span-quantity-inactive-warning');

    btnEdit.style.display = 'block';
    $.ajax({
        url: 'server/db_admin_get_product_detail.php',
        type: 'POST',
        dataType: 'json',
        data: {code: code},
        success: (result) => {
            console.log(result);
            if (result.statusCode == 200) {
                dropdownState(viewDropdown, false);
                txtInputState(txtInput, false);
                txtInputQuantity.value = '';
                chkBoxActive.checked = false;
                chkBoxActive.setAttribute('disabled', '');
                chkBoxActive.setAttribute('data-code', code);
                chkBoxActive.removeAttribute('data-location');

                btnClose.innerHTML = 'Close';
                btnEdit.innerHTML = 'Edit';
    
                // // Set Text Field, Dropdown and Image
                txtTitle.innerHTML = 'Merhcandise ID: #' + result.id;
                txtInputName.value = result.name;
                txtInputDesc.value = result.desc;
                imgInputCurrent.style.display = 'block';
                imgInputCurrent.src = result.img;

                imgInputUpload.value = '';
                spanQuantityWarning.style.display = 'none';

                btnLocation.innerHTML = 'Select location:';
                btnLocation.dataset.code = '0';

                btnCategory.dataset.code = result.cate_id;
                btnGroup.dataset.code = result.group_id;
                
                // End Set Text Field, Dropdown and Image

                // Start Set Dropdown Button (Category) 
                dropdownCategory.forEach(dropdown => {
                    dropdown.addEventListener('click', categoryHandle);
                    if (result.cate_id == dropdown.dataset.code) {
                        btnCategory.innerHTML = dropdown.innerHTML;
                    }
                })
                // End Set Dropdown Button (Category)
                
                
                // Start Set Dropdown Button (Location)
                dropdownLocation.forEach(dropdown => {
                    dropdown.addEventListener('click', locationHandle);
                })
                // End Set Dropdown Button (Location)


                //Start Set Dropdown Button (Group)
                dropdownGroup.forEach(dropdown => {

                    dropdown.addEventListener('click', groupHandle);
                    if (result.group_id == dropdown.dataset.code) {
                        btnGroup.innerHTML = dropdown.innerHTML;
                    }
                })
                // End Set Dropdown Button (Group)


                // Start Edit Button Function
                functionHandler = (e) => {
                    if (btnEdit.innerHTML == 'Edit') {
                        dropdownState(viewDropdown, true);
                        txtInputState(txtInputEnable, true);
    
                        btnEdit.innerHTML = 'Update';

                    } else if (btnEdit.innerHTML == 'Update') { // Update Button
                        let updateProductBoolean = true;
                        if (txtInputName.value.trim() == '') {
                            txtInputName.classList.add('error-input');
                            updateProductBoolean = false;
                        }
                        if (txtInputDesc.value.trim() == '') {
                            txtInputDesc.classList.add('error-input');
                            updateProductBoolean = false;
                        }

                        if (btnLocation.dataset.code != '0') {

                            let txtInputQuantityInt = parseInt(txtInputQuantity.value.trim());

                            if (isNaN(txtInputQuantityInt)) {
                                txtInputQuantity.classList.add('error-input');
                                updateProductBoolean = false;
                            } else if (txtInputQuantityInt < 0) {
                                txtInputQuantity.classList.add('error-input');
                                updateProductBoolean = false;
                            }

                        } 

                        if (btnCategory.dataset.code == 0) {
                            btnCategory.classList.add('error-input');
                            updateProductBoolean = false;
                        }

                        if (btnGroup.dataset.code == 0) {
                            btnGroup.classList.add('error-input');
                            updateProductBoolean = false;
                        }

                        if (updateProductBoolean) {
                            updateProduct(code, txtInputName.value.trim(), btnLocation.dataset.code, parseInt(txtInputQuantity.value.trim()), btnGroup.dataset.code, btnCategory.dataset.code, txtInputDesc.value.trim(), imgInputUpload.files[0], result.img);
                        }
                    }
                }
                // End Edit Button Function


                // Start Location Dropdown Function
                locationHandler = (e) => {
                    let locationName = e.target.innerHTML;
                    let locationId = e.target.dataset.code;
                    
                    btnLocation.innerHTML = locationName;
                    btnLocation.dataset.code = locationId;
           

                    if (locationId != 0) {
                        txtInputState(txtInputQuantity, true);
       

                        let productStatus = result[locationId][1];
                        let productQuantity = result[locationId][0]; 

                        if (productStatus == 0) {
                            spanQuantityWarning.style.display = 'inline';
                            txtInputQuantity.value = productQuantity;
                            txtInputState(txtInputQuantity, false);
                        } else {
                            spanQuantityWarning.style.display = 'none';
                            txtInputState(txtInputQuantity, true);
                            txtInputQuantity.value = productQuantity;
                        }

                        if (locationId != 1) {
                            chkBoxActive.removeAttribute('disabled');
                            if (productStatus == 1) {
                                chkBoxActive.checked = true;
                            } else {
                                chkBoxActive.checked = false;
                            }
                            chkBoxActive.setAttribute('data-location', locationId);
                        } else {
                            chkBoxActive.checked = true;
                            chkBoxActive.setAttribute('disabled', '');
                        }
                    }
                }
                // End Location Dropdown Function

                // Start Category Dropdown Function
                categoryHandler = (e) => {
                    let categoryName = e.target.innerHTML;
                    let categoryId = e.target.dataset.code;

                    btnCategory.innerHTML = categoryName;
                    btnCategory.dataset.code = categoryId;
                }
                // End Category Dropdown Function

                // Start Group Dropdown Function
                groupHandler = (e) => {
                    let groupName = e.target.innerHTML;
                    let groupId = e.target.dataset.code;

                    btnGroup.innerHTML = groupName;
                    btnGroup.dataset.code = groupId;
                }
                // End Group Dropdown Function

                btnEdit.addEventListener('click', handle);
    
                $('#id-viewEditBox').modal('show');
            } else if (result.statusCode == 400) {
                console.log(result.message);
            }
        }
    });

}

// End View Popup


function updateProduct(code, name, location, quantity, group, category, desc, img, currentImg) {

    const formData = new FormData();
    formData.append('code', code);
    formData.append('name', name);
    if (location != 0) {
        formData.append('location', location);
        formData.append('quantity', quantity);
    }
    formData.append('group', group);
    formData.append('category', category);
    formData.append('desc', desc);
    formData.append('img', img);
    formData.append('currentImg', currentImg);

    $.ajax({
        url: 'server/db_admin_update_product_detail.php',
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false,
        dataType: 'json',
        success: (result) => {
            console.log(result)
            if (result.statusCode == 200) {
                window.location.reload(true);
            }
        }
    })
}


function setProductStatus(e) {
    let productStatus = e.target.checked;
    let productId = e.target.dataset.code;
    let locationId = e.target.dataset.location;

    productStatus = Number(productStatus);
    
    $.ajax({
        url: 'server/db_admin_set_product_status.php',
        type: 'POST',
        data: { productStatus: productStatus,
                productId: productId,
                locationId: locationId},
        dataType: 'json',
        success: (result) => {
            let statusCode = result.statusCode;
            if (statusCode == 200) {
                const spanInActiveWarning = document.getElementById('id-span-quantity-inactive-warning');
                const txtInputQuantity = document.getElementById('id-txt-view-quantity');

                if (productStatus) {
                    spanInActiveWarning.style.display = 'none';
                    txtInputState(txtInputQuantity, true);
                } else {
                    spanInActiveWarning.style.display = 'inline';
                    txtInputState(txtInputQuantity, false);
                }

            } else if (statusCode == 500) {
                console.log(result.message);
            }
        }
    });
}

// Common Function

function closePopup(e) {
    const grandparentDiv = e.target.parentElement.parentElement.parentElement.parentElement;
    $(grandparentDiv).modal('hide');
}

function dropdownState(dropdown, dropdownBoolean) {

    if (dropdown.length !== undefined) { // If multiple dropdown
        dropdown.forEach(drop => {
            if (dropdownBoolean) {
                drop.style.pointerEvents = 'auto';
                drop.removeAttribute('tabindex');
                drop.removeAttribute('disabled');
            } else {
                drop.style.pointerEvents = 'none';
                drop.setAttribute('tabindex', -1);
                drop.setAttribute('disabled', '');
            }
        })

    } else { 
        if (dropdownBoolean) {
            dropdown.style.pointerEvents = 'auto';
            dropdown.removeAttribute('tabindex');
        } else {
            dropdown.style.pointerEvents = 'none';
            dropdown.setAttribute('tabindex', -1);
        }
    }


}



function txtInputState(txtInput, txtInputBoolean) {

    if (txtInput.length !== undefined) { // If multiple text input
        txtInput.forEach(input => {
            if (input) {
                input.disabled = !txtInputBoolean;
            } else {
                input.disabled = !txtInputBoolean;
            }
        })
    } else {
        if (txtInputBoolean) {
            txtInput.disabled = !txtInputBoolean;
        } else {
            txtInput.disabled = !txtInputBoolean;
        }
    }
}

function returnProduct(evt, productId, productName) {

    const dropdownReturnProductDiv = document.getElementById('id-div-return-product');
    const btnDropdownReturnProductDiv = document.getElementById('id-btn-dropdown-return-product');
    const btnReturnProductLocation = document.getElementById('id-btn-return-product');
    const btnReturnProductConfirm = document.getElementById('id-btn-confirm-return-product');
    console.log(btnReturnProductConfirm);
 
    btnDropdownReturnProductDiv.innerHTML = 'Select location: ';
    btnReturnProductLocation.disabled = true;

    $.ajax({
        url: 'server/db_admin_get_location_quantity.php',
        type: 'POST',
        data: {productId: productId},
        dataType: 'json',
        success: (result) => {
            let returnCode = result.returnCode;
            if (returnCode == 200) {


                let producQuantityList = result.message;
                producQuantityList.forEach(list => {
                    let locationCode = Object.keys(list)[0];
                    let locationName = Object.values(list)[0];
                    let locationQuantity = Object.values(list)[1];


                    if (locationCode != 1) {
                        const a = document.createElement('a');
                        a.className = 'dropdown-item';
                        a.innerHTML = locationName;
    
                        if (locationQuantity <= 0) {
                            a.classList.add('disabled');
                            a.style.pointerEvents = 'none';
                        }
    
                        a.addEventListener('click', () => {
                            btnDropdownReturnProductDiv.innerHTML = locationName;
                            btnReturnProductLocation.disabled = false;
                            $('#id-span-return-product-name').html(productName);
                            $('#id-span-return-product-location').html(locationName);
                            btnReturnProductConfirm.setAttribute('data-code', locationCode);
                            btnReturnProductConfirm.setAttribute('data-product', productId);
                        })
    
                        dropdownReturnProductDiv.appendChild(a);

                    }
                })

                // const dropdownReturnProductItems = document.querySelectorAll('.div-return-product a');
                // dropdownReturnProductItems.forEach(item => {
                //     item.addEventListener('click', () => {
                        
                //     })
                // })
            }

        }
    })

    $("#span-title-return-product").html(productName);
    $('#id-box-return-product').modal('show');
}

function enabledBtn(btn) {
    btn.classList.remove('disabled-pagination');
    btn.style.pointerEvents = 'auto';
}

function disabledBtn(btn) {
    btn.classList.add('disabled-pagination');
    btn.style.pointerEvents = 'none';
}