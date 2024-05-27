let tbody;
let currentPage = 0;
let btnNext;
let btnPrevious;
let currentPageHTML;
let currentStatus = 'all';
let sortDate;

window.onload = () => {
    
    tbody = document.querySelector('.table-body');
    btnPrevious = document.querySelector('.previous');
    btnNext = document.querySelector('.next');
    currentPageHTML = document.querySelector('.current');
    sortDate = document.getElementById('id-tbl-col-date');
    let txtSearch = document.querySelector('.search-bar');
    let btnSearch = document.querySelector('.search_icon');
    let searchForm = document.getElementById('id-searchForm');

    let filterDrpDown = document.querySelectorAll('.dropdown-list a');
    let currentActiveTab = filterDrpDown[0];

    const btnAddNewRequest = document.getElementById('id-btn-new-request');


    filterDrpDown.forEach(filter => {
        filter.addEventListener('click', (e) => {
                currentActiveTab.classList.remove('dropdown-active');
                currentActiveTab = e.target;
                e.target.classList.add('dropdown-active');
                let requestStatus = e.target.dataset.status;
                currentStatus = requestStatus;
                currentPage = 0;
                currentPageHTML.innerHTML = currentPage + 1;
                displayRequest(requestStatus, currentPage, null, null);
        })
    })

    // ---------- Pagination ------------- //
    btnPrevious.addEventListener('click', (e) => {
        currentPage--;
        currentPageHTML.innerHTML = currentPage + 1;
        displayRequest(currentStatus, currentPage * 10, null, null);
    })

    btnNext.addEventListener('click', (e) => {  
        currentPage++;
        currentPageHTML.innerHTML = currentPage + 1;
        displayRequest(currentStatus, currentPage * 10, null, null);
    })

    // ---------- End Pagination ------------- //

    sortDate.addEventListener('click', (e) => {

        // let requestRows = [].slice.call(tbody.querySelectorAll('tr'));

        // console.log(convertDate(requestRows[9].childNodes[3].innerHTML));

        // // requestRows.sort(function(a, b) {
        // //     return convertDate(a.childNodes[3].innerHTML) - convertDate(b.childNodes[3].innerHTML);
        // // })



        // console.log(requestRows);


        sortDate = e.target.dataset.sort;
        displayRequest(currentStatus, currentPage * 10, sortDate);
        e.target.dataset.sort = (sortDate == 'DESC') ? 'ASC' : 'DESC';
        //displayRequest(requestStatus, currentPage);
    })
 

    // Merge into one (Function) later . (index.js & admin_merchandise.js)

    btnSearch.addEventListener('click', (e) => {
        e.preventDefault();
        if (txtSearch.value.trim() != '') {
            currentStatus = 'all';
            currentPage = 0;
            currentPageHTML.innerHTML = currentPage + 1;
            displayRequest(currentStatus, currentPage, null, txtSearch.value);
            txtSearch.value = '';
        } else {
            currentStatus = 'all';
            currentPage = 0;
            currentPageHTML.innerHTML = currentPage + 1;
            displayRequest(currentStatus, currentPage, null, null);
            txtSearch.value = '';
            
        }
    })

    searchForm.addEventListener('submit', (e) => {
        e.preventDefault();
        if (txtSearch.value.trim() != '') {
            currentStatus = 'all';
            currentPage = 0;
            currentPageHTML.innerHTML = currentPage + 1;
            displayRequest(currentStatus, currentPage, null, txtSearch.value);
            txtSearch.value = '';
        } else {
            currentStatus = 'all';
            currentPage = 0;
            currentPageHTML.innerHTML = currentPage + 1;
            displayRequest(currentStatus, currentPage, null, null);
            txtSearch.value = '';
        }
    })

    btnAddNewRequest.addEventListener('click', () => {
        window.location.href = 'request.php';
    })

    disabledBtn(btnNext);
    disabledBtn(btnPrevious);
    displayRequest(currentStatus, currentPage, null, null);

}


function enabledBtn(btn) {
    btn.classList.remove('disabled-pagination');
    btn.style.pointerEvents = 'auto';
}

function disabledBtn(btn) {
    btn.classList.add('disabled-pagination');
    btn.style.pointerEvents = 'none';
}


function convertDate(d) {
    let p = d.split('/');
    return +(p[2]+p[1]+p[0]);
}


function displayRequest(filter, offset, dateSort, searchTxt) {
    $.ajax({
        method: 'GET',
        url: 'server/db_request_list.php',
        dataType: 'json',
        data: { filter: filter,
                offset: offset,
                dateSort: dateSort,
                searchTxt: searchTxt,
                requestType: 'normal'},
        success: (result) => {
            let totalRow = result.totalRow;
            tbody.innerHTML = result.html;

            if (totalRow > 10) {
                enabledBtn(btnNext);
            } else {
                disabledBtn(btnNext);
            }

            if (currentPage != 0) {
                enabledBtn(btnPrevious);
            } else {
                disabledBtn(btnPrevious);
            }

            assignPopup();
        }
    });
}

function assignPopup() {
    let requestPopup = document.querySelectorAll('.request-popup');
    requestPopup.forEach(popup => {
        if (popup.classList.contains('view-popup')) {
            popup.addEventListener('click', (e) => {
        
                displayPopup(e);
            });
        }
    })
}


function displayPopup(e) {
    e.preventDefault();
    let requestId = e.target.dataset.requestId;
    let requestStatus = e.target.dataset.requestStatus;
    let requestLocation = e.target.dataset.location;
    let popupDiv = document.querySelector('.modal-popup');

    $.ajax({
        type: 'POST',
        url: 'server/db_get_detail_popup.php',
        data: {
            data_requestId: requestId,
            data_requestStatus: requestStatus,
            data_location: requestLocation
        },
        success: (result) => {
            popupDiv.innerHTML = result;
            let myModal = new bootstrap.Modal(document.getElementById('viewPopup'), {})

            let modal = document.getElementById('viewPopup');
            myModal.toggle();

            // document.querySelector('.close-popup').addEventListener('click', () => {
            //     myModal.toggle();
            //     modal.remove();
            // })

        }
    });
}

function showComment(productId, requestId) {
    
    if (productId.trim() != '' && requestId.trim() != '') {
        $.ajax({
            url: 'server/db_get_mer_history.php',
            type: 'POST',
            data: { requestId: requestId,
                    productId: productId},
            dataType: 'json',
            success: (result) => {
                const commentBodyBoxModal = document.getElementById('id-modal-body-comment');
                let historyArray = result.historyArray;
                console.log(historyArray);
                commentBodyBoxModal.innerHTML = '';
                historyArray.forEach(history => {
                    const p = document.createElement('p');
                    p.innerHTML = history;
                    commentBodyBoxModal.append(p);
                });
                // $('#id-modal-comment').modal('show');
                console.log(result);
                // console.log(result.historyArray[0]);
                

            }
        });
    }
}


