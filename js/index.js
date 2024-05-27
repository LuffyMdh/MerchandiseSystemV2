let btnNextPage;
let btnPrevPage;
let currentPage = 0;
let currentLocation;
let groupId;
let tempDisplayProductDiv;
let cateCode;
let btnDeleteAll;
let btnProductClicked;

window.onload = () => {
    let categoryItems = document.querySelectorAll('.dropdown-cate-type .dropdown-list .dropdown-item');
    let displayProductDiv = document.getElementById('display-result');
    tempDisplayProductDiv = displayProductDiv;
    let currentActiveTab = categoryItems[0];
    let currentPageHTML = document.querySelector('.current');
    let categoryDrpdown = document.querySelector('.dropdown-cate-type .dropdown-toggle');
    let txtSearch = document.querySelector('.search-bar');
    let btnSearch = document.querySelector('.search_icon');
    let btnGrpCategory = document.querySelectorAll('.group-category ul li a');
    btnDeleteAll = document.getElementById('id-btn-remove');

    let btnLocationDropdown = document.getElementById('id-btn-dropdown-location');
    let locationDropdownItems = document.querySelectorAll('.dropdown-cate-location a');

    if (locationDropdownItems.length != 0) {
        locationDropdownItems[0].classList.add('dropdown-active');
        btnLocationDropdown.innerHTML = locationDropdownItems[0].innerHTML;
        currentLocation = locationDropdownItems[0].dataset.locationid;
    } else {
        currentLocation = 0;
    }

    cateCode = 'all';
    groupId = 'all';
    
    let groupCurrentActiveTab = btnGrpCategory[0];
    let locationCurrentActiveTab = locationDropdownItems[0];

    btnNextPage = document.querySelector('.next');
    btnPrevPage = document.querySelector('.previous');
   
    let searchEnteredValue = '';

    fnAddToCart(cateCode, currentLocation, displayProductDiv, 0, searchEnteredValue, groupId);

    categoryItems.forEach(item => {
        item.addEventListener('click', (e) => {
            disabledBtn(btnNextPage);
            disabledBtn(btnPrevPage);
            currentPage = 0;
            currentPageHTML.innerHTML = currentPage + 1;
            currentActiveTab.classList.remove('dropdown-active');
            currentActiveTab = e.target;
            e.target.classList.add('dropdown-active');
            categoryDrpdown.innerHTML = e.target.innerHTML;
            cateCode = e.target.dataset.prodCate;
            txtSearch.value = '';
            searchEnteredValue = '';
            fnAddToCart(cateCode, currentLocation, displayProductDiv, 0, searchEnteredValue, groupId);
        })  
    });

    disabledBtn(btnNextPage);
    disabledBtn(btnPrevPage);

    btnNextPage.addEventListener('click', (e) => {
       
        currentPage++;
        fnAddToCart(cateCode, currentLocation, displayProductDiv, currentPage * 10, searchEnteredValue, groupId);
        currentPageHTML.innerHTML = currentPage + 1;
        window.scrollTo(0, 0);
        enabledBtn(btnPrevPage);
    })

    btnPrevPage.addEventListener('click', () => {
        currentPage--;
        fnAddToCart(cateCode, currentLocation, displayProductDiv, currentPage * 10, searchEnteredValue, groupId);
        currentPageHTML.innerHTML = currentPage + 1;
        window.scrollTo(0, 0);
        enabledBtn(btnNextPage);

        if (currentPage == 0) {
            disabledBtn(btnPrevPage);
        }
    })

    btnSearch.addEventListener('click', (e) => {
        e.preventDefault();
        if (txtSearch.value.trim() != '') {
            cateCode = 'all';
            groupId = 'all';

            disabledBtn(btnNextPage);
            disabledBtn(btnPrevPage);
            currentPage = 0;
            currentPageHTML.innerHTML = currentPage + 1;
            searchEnteredValue = txtSearch.value;

            fnAddToCart(cateCode, currentLocation, displayProductDiv, 0, searchEnteredValue, groupId);

            groupCurrentActiveTab.classList.remove('active-tab');
            btnGrpCategory[0].classList.add('active-tab');
            groupCurrentActiveTab = btnGrpCategory[0];

            currentActiveTab.classList.remove('dropdown-active');
            categoryItems[0].classList.add('dropdown-active');
            currentActiveTab = categoryItems[0];
            categoryDrpdown.innerHTML = 'Category';
        } else {            
            disabledBtn(btnNextPage);
            disabledBtn(btnPrevPage);
            currentPage = 0;
            currentPageHTML.innerHTML = currentPage + 1;
            txtSearch.value = '';
            searchEnteredValue = txtSearch.value

            fnAddToCart(cateCode, currentLocation, displayProductDiv, 0, searchEnteredValue, groupId);
        }
    })

    let searchForm = document.getElementById('id-searchForm');

    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (txtSearch.value.trim() != '') {
            cateCode = 'all';
            groupId = 'all';

            disabledBtn(btnNextPage);
            disabledBtn(btnPrevPage);
            currentPage = 0;
            currentPageHTML.innerHTML = currentPage + 1;
            searchEnteredValue = txtSearch.value;
            fnAddToCart(cateCode, currentLocation, displayProductDiv, 0, searchEnteredValue, groupId);
            // txtSearch.value = '';
            

            groupCurrentActiveTab.classList.remove('active-tab');
            btnGrpCategory[0].classList.add('active-tab');
            groupCurrentActiveTab = btnGrpCategory[0];
            
            currentActiveTab.classList.remove('dropdown-active');
            categoryItems[0].classList.add('dropdown-active');
            currentActiveTab = categoryItems[0];
            categoryDrpdown.innerHTML = 'Category';
        } else {
            cateCode = 'all';
            groupId = 'all';

            disabledBtn(btnNextPage);
            disabledBtn(btnPrevPage);
            currentPage = 0;
            currentPageHTML.innerHTML = currentPage + 1;
            txtSearch.value = '';
            searchEnteredValue = txtSearch.value;
            fnAddToCart(cateCode, currentLocation, displayProductDiv, 0, searchEnteredValue, groupId);


            groupCurrentActiveTab.classList.remove('active-tab');
            btnGrpCategory[0].classList.add('active-tab');
            groupCurrentActiveTab = btnGrpCategory[0];

            currentActiveTab.classList.remove('dropdown-active');
            categoryItems[0].classList.add('dropdown-active');
            currentActiveTab = categoryItems[0];
            categoryDrpdown.innerHTML = 'Category';
           
        }
    })

    locationDropdownItems.forEach((item) => {
        item.addEventListener('click', (e) => {
            let getLocationId = e.target.dataset.locationid;
            let locationName = e.target.innerHTML;
            btnLocationDropdown.innerHTML = locationName;
            currentLocation = getLocationId;
            locationCurrentActiveTab.classList.remove('dropdown-active');
            locationCurrentActiveTab = e.target;
            e.target.classList.add('dropdown-active');

            txtSearch.value = '';
            searchEnteredValue = '';
            currentPage = 0;
            currentPageHTML.innerHTML = currentPage + 1;
            disabledBtn(btnNextPage);
            disabledBtn(btnPrevPage);



            fnAddToCart(cateCode, currentLocation, displayProductDiv, 0, searchEnteredValue, groupId);
        })
    })

    btnGrpCategory.forEach((group) => {
        group.addEventListener('click', (e) => {
            groupCurrentActiveTab.classList.remove('active-tab');
            e.target.classList.add('active-tab');
            groupCurrentActiveTab = e.target;
            groupId = e.target.dataset.groupid;

            txtSearch.value = '';
            searchEnteredValue = '';
            currentPage = 0;
            currentPageHTML.innerHTML = currentPage + 1;
            disabledBtn(btnNextPage);
            disabledBtn(btnPrevPage);

            fnAddToCart(cateCode, currentLocation, displayProductDiv, 0, searchEnteredValue, groupId);
        })
    })

};


function onSearch(e) {
    e.preventDefault();
}

function fnAddToCart(cate, locationId, displayProductDiv, offset, searchTxt, groupCategory) {
    displayProduct(cate, locationId, displayProductDiv, offset, searchTxt, groupCategory)
    .done((result) => {

    });
}

function addItemToCart(e, code) { 
    addToCart(e, code);
}

function removeItemAll(e) {
    let removeProductCode = e.target.dataset.productid;

    let cartId = e.target.dataset.cartid;
    $.ajax({
        url: 'server/db_cart_remove_all.php',
        type: 'POST',
        data: {cartId: cartId},
        success: (result) => {

            if (result == 200) {
                addToCart(btnProductClicked, removeProductCode);
                $('.modal-remove-from-cart').modal('hide');
            }

            //console.log(result);
            
        }
    })
}

function addToCart(e, code) {
    $.ajax({
        url: 'server/db_add_cart.php',
        type: 'POST',
        data: { code: code,
                location: currentLocation},
        success: (result) => {
            if (result == 200) {
                e.target.innerHTML = 'Already in cart';
                btnAddCartStatus(e.target, true);
                console.log("Added to cart");
            } else if (result == 400) {
                $('.modal-remove-from-cart').modal('show');
                btnDeleteAll.setAttribute('data-productid', code);
                btnProductClicked = e;
            }

            // console.log(result);
        }
    });
}

function displayProduct(cateCode, locationId, div, offset, searchTxt, groupCategory) {
    return $.ajax({
        type: 'POST',
        url: 'server/display_product.php',
        dataType: 'json',
        data: {
            code: cateCode,
            locationId: locationId,
            offset: offset,
            searchTxt: searchTxt,
            groupCate: groupCategory
        },
        success: (result) => {
            let totalPages = result.totalPages;
            
            if (result.html != '') {
                div.innerHTML = result.html;
               
            } else {
                div.innerHTML = '<p style="margin: 10px;">No merchandise found!</p>';
            }


            if (totalPages > 10) {
                enabledBtn(btnNextPage);
            } else {
                disabledBtn(btnNextPage);
            }
        }
    })
}

function enabledBtn(btn) {
    btn.classList.remove('disabled-pagination');
    btn.style.pointerEvents = 'auto';
}

function disabledBtn(btn) {
    btn.classList.add('disabled-pagination');
    btn.style.pointerEvents = 'none';
}

function btnAddCartStatus(btn, status) {
    if (status) {
        btn.classList.add('btn-gray-style');
        btn.classList.add('disabled-all-btn');
        btn.blur();
        btn.setAttribute('tabindex', '-1');
        btn.classList.remove('btn-default-style');
    } else {
        btn.classList.remove('btn-gray-style');
        btn.classList.remove('disabled-all-btn');
        btn.classList.add('btn-default-style');
    }

}