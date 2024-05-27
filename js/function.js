

function readNoti() {
    $.ajax({
        url: 'server/db_get_noti.php',
        type: 'POST',
        data: {data: 'POST'},
        success: (result) => {
            getNotification();
        }
    });
}

function isNumber(evt) {
    let charCode = evt.charCode;

    if ((charCode < 48 || charCode > 57)) {
        return false;
    }

    return true;

}

function downloadDoc(e, code, userId) {
    e.preventDefault();
    $.ajax({
        url: 'server/db_download_doc.php',
        type: 'POST',
        data: { code: code,
                userId: userId},
        dataType: 'json',
        success: (result) => {
           //console.log(result);

            let statusCode = Object.keys(result)[0]
            if (statusCode == 200) {
                let zipFileLink = result[200];
                let httpProtocol = location.protocol;
                zipFileLink = httpProtocol + '\\\\' + zipFileLink;
                let a = document.createElement('a');
                a.download = code + '.zip';
                a.href = zipFileLink;
                a.setAttribute('taget', '_blank');
                a.click();
                a.remove();
            }
        }
    })
}

async function fetchData() {
    const response = await fetch('https://jian.sh/malaysia-api/state/v1/all.json');
    const data = await response.json();
    return data;
}


// function timeInverval() {
//     let sessionTimeout = 100;
//     setInterval(() => {

//         console.log(sessionTimeout);        
//         sessionTimeout--;
//     }, 1000)
// }

// const myFunc = timeInverval;

// myFunc();





// $(document).on('click', () => {
//     console.log(sessionTimeout);
// })