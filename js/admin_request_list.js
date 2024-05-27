const urlRequestId = window.location.search;
const urlParams = new URLSearchParams(urlRequestId);
let statusCode = urlParams.get('catecode');

window.onload = () => {

    let prevBtn = document.querySelector('.pagination .previous');
    let nextBtn = document.querySelector('.pagination .next');
    let current = document.querySelector('.pagination .current');
    let currentPage = current.innerHTML;

    

    disabledBtn(prevBtn);
    disabledBtn(nextBtn);

    refreshList(nextBtn, prevBtn, 10, currentPage, 'pending');

    nextBtn.addEventListener('click', (e) => {
        pageCtrlBtn(e, nextBtn, prevBtn, true);
    })

    prevBtn.addEventListener('click', (e) => {
        pageCtrlBtn(e, nextBtn, prevBtn, false);
    })
}

function getRequestAmount() {
    
    return $.ajax({
        url: 'server/db_request_list.php',
        type: 'GET',
        dataType: 'json',
        data: {requestType: 'admin',
            requestList: 'number'},
        success: (result) => {
        }
    })
}

function getRequestList(filter, currentPage, offset) {
    $.ajax({
        url: 'server/db_request_list.php',
        type: 'GET',
        data: {requestType: 'admin',
                requestList: 'list',
                filter: filter,
                offset: offset,
                currentPage: currentPage},
        success: (result) => {
            let tbody = document.querySelector('.table tbody');
            tbody.innerHTML = result;
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

function refreshList(nextBtn, prevBtn, limit, currentPage, filter) {
    getRequestAmount()
    .done((result) => {
        let filterIndex = '';
        switch (filter) {
            case 'accepted': 
                filterIndex = 1;
                break;
            case 'rejected':
                filterIndex = -1;
                break;
            case 'pending':
                filterIndex =  0;
                break;
            default:
                filterIndex = 'all';
                break;
        }


        let statusIcon = document.querySelectorAll('.mini-icon .status-icon');
        let totalPages = result[filter]['totalPages'];

        statusIcon.forEach(icon => {
            icon.innerHTML = result[icon.classList[2]]['totalList'];
        })

        currentPage--;
        getRequestList(filterIndex, limit, currentPage*10);
        currentPage++;

        if (currentPage != totalPages && totalPages != 0) {
            enabledBtn(nextBtn);
        } else {
            disabledBtn(nextBtn);
        }

        nextBtn.dataset.filter = filter;
        prevBtn.dataset.filter = filter;

        
        
    });
}

function changeStatusList(evt, filter) {
    let prevBtn = document.querySelector('.pagination .previous');
    let nextBtn = document.querySelector('.pagination .next');
    let current = document.querySelector('.pagination .current');
    let currentPage = current.innerHTML;
    current.innerHTML = 1;
    currentPage = 1;
    disabledBtn(prevBtn);
    refreshList(nextBtn, prevBtn, 10, currentPage, filter);
    
}

function pageCtrlBtn(e, nextBtn, prevBtn, btnType) {
        let filter = e.target.dataset.filter;
        let current = document.querySelector('.pagination .current');
        let currentPage = current.innerHTML;

        console.log(e);

        if (btnType) {
            currentPage++;
            enabledBtn(prevBtn);
        } else {
            currentPage--;
            if (currentPage == 1) {
                disabledBtn(prevBtn);
            }
        }

        refreshList(nextBtn, prevBtn, 10, currentPage, filter);
        current.innerHTML = currentPage;
        
}

function viewDetail(requestId) {
  
    $.ajax({
        url: 'server/db_admin_request_detail.php', // To check if request ID is valid
        dataType: "json",
        type: 'GET',
        data: {requestId: requestId},
        success: (result) => {
            let returnCode = result.returnCode;
            let requestStatus = result.requestStatus;
            let requestLocation = result.requestLocation;

            if (returnCode == 200) {
                
                window.location.href = 'admin_request_detail.php?request_id=' + requestId + '&status=' + requestStatus + '&location=' + requestLocation;
                
            } else if (returnCode == 404) {
                console.log('Invalid Request ID');
            }
        }
    })
}


