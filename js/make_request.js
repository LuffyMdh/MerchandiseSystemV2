let filesCounter = 1;
let groupCounter = 1;
let pAddDoc;
let pMaxDoc;
let docErrorMsg;
let uploadedFile;
let totalGroup;
let selectedGroup = [];

window.onload = (e) => {
    const btnMakeReq = document.querySelector('.btn-make-req');
    const requestPurpose = document.getElementById('id-request-purpose');
    const porErrorMsg = document.querySelector('.por-err-msg');
    const btnClosePopup = document.getElementById('id-btn-close-popup');
    const btnReturn = document.getElementById('id-btnReturn');
    const btnAddDocument = document.getElementById('id-add-document');
    const inputFileDiv = document.querySelector('.div-input-file');

    const btnLocationDropdown = document.getElementById('id-btn-dropdown-location');
    const locationItems = document.querySelectorAll('.location-dropdown a');

    //Group Section Assign
    // const inputGroupDiv = document.querySelector('.input-group-group');
    // const btnAddGroup = document.getElementById('id-add-group');
    // totalGroup = document.getElementById('id-group-category');
    // totalGroup = totalGroup.children;

    // totalGroup = Array.prototype.slice.call(totalGroup);
    // totalGroup = totalGroup.map((group) => {
    //     return group;
    // })



    


    pAddDoc = document.querySelector('.p-add-document');
    pMaxDoc = document.querySelector('.p-max-document');
    docErrorMsg = document.querySelector('.doc-err-msg');
    uploadedFile = document.querySelectorAll('.fileAttachment');

    btnClosePopup.addEventListener('click', () => {
        $('.modal-done-request').modal('hide');
        
    });

    $('#id-request-done').on('hidden.bs.modal', () => {
        window.location.href = 'index.php';
    })


    getRequester();
    // getCartItem();

    btnMakeReq.addEventListener('click', (e) => {
        
        let uploadedFileArray = [];
        let makeRequestNow = false;
        uploadedFile = document.querySelectorAll('.fileAttachment');

        uploadedFile.forEach(file => {
            if (file.files[0] != null) {
                uploadedFileArray.push(file.files[0]);
            }
        });

        if (requestPurpose.value.trim() == '' || uploadedFileArray.length == 0 || btnLocationDropdown.dataset.location == undefined) {

            if (requestPurpose.value.trim() == '') {
                porErrorMsg.scrollIntoView();
                porErrorMsg.style.display = 'inline-block';
                requestPurpose.classList.add('error-input');
            }

            if (uploadedFileArray.length == 0) {
                docErrorMsg.style.display = 'block';
                docErrorMsg.scrollIntoView();
                uploadedFile.forEach(file => {
                    if (file.files[0] == null) {
                        file.classList.add('error-input');
                    }
                })

            }

            if (btnLocationDropdown.dataset.location == undefined) {
                btnLocationDropdown.classList.add('error-input');
            }

        } else {
            let reqPurpose = requestPurpose.value;
            let location = btnLocationDropdown.dataset.location;
            makeRequest(reqPurpose, uploadedFileArray, location);
        }
    })

    requestPurpose.addEventListener('keyup', () => {
        requestPurpose.classList.remove('error-input');
        porErrorMsg.style.display = 'none';
    })

    btnReturn.addEventListener('click', () =>{ 
        window.location.href = "request_list.php";
    })

    btnAddDocument.addEventListener('click', (e) => {

        if (filesCounter < 5) {
            filesCounter++;
            let inputDiv = document.createElement('div');
            let input = document.createElement('input');
            let xMark = document.createElement('i');

            inputDiv.className = 'input-file';
    
            input.className = 'form-control fileAttachment';
            input.type = 'file';
            input.setAttribute('accept', 'image/jpeg, image/png, application/pdf');
            input.setAttribute('onchange', 'validateFileInput(event)');

            xMark.className = 'bi bi-x';
            xMark.setAttribute('onclick', 'removeInputField(event)');
    
            inputDiv.appendChild(input);
            inputDiv.appendChild(xMark);
            inputFileDiv.appendChild(inputDiv);

            if (filesCounter == 5) {
                pAddDoc.style.display = 'none';
                pMaxDoc.style.display = 'block';
            }
        }
    })

    locationItems.forEach(item => {
        item.addEventListener('click', (e) => {
            let locationId = e.target.dataset.location;
            let locationName = e.target.innerHTML;

            btnLocationDropdown.innerHTML = locationName;
            btnLocationDropdown.setAttribute('data-location', locationId);

            btnLocationDropdown.classList.remove('error-input');
        })
    })

    // btnAddGroup.addEventListener('click', (e) => {
        
    //     if (groupCounter < 5) {
    //         groupCounter++;
    //         let groupDiv = document.createElement('div');
    //         let selectInput = document.createElement('select');
    //         let txtGroupInput = document.createElement('input');
    //         let spanText = document.createElement('span');

    //         selectInput.className = 'form-select select-group';
    //         selectInput.id = 'id-group-category';
    //         selectInput.setAttribute('onchange', 'adjustGroup(event)');

    //         for (let i = 0; i < totalGroup.length; i++) {
    //             let option = document.createElement('option');
    //             let value = totalGroup[i].value;
    //             let optionTxt = totalGroup[i].innerHTML;
                
    //             option.value = value;
    //             option.innerHTML = optionTxt;
    //             selectInput.appendChild(option);
    //         }


    //         txtGroupInput.type = 'text';
    //         txtGroupInput.className = 'form-control';
    //         txtGroupInput.id = 'id-input-group-pax';

    //         spanText.className = 'span-group-pax';
    //         spanText.innerHTML = 'pax';

    //         groupDiv.className = 'input-group';
           


    //         groupDiv.appendChild(selectInput);
    //         groupDiv.appendChild(txtGroupInput);
    //         groupDiv.appendChild(spanText);

    //         inputGroupDiv.appendChild(groupDiv);
    //     } else {

    //     }

    // });
}


function getRequester() {
    let inputs = document.querySelectorAll('.requestor-detail .requester-input');

    $.ajax({
        url: 'server/db_get_user_detail.php',
        type: 'GET',
        dataType: "json",
        data: {},
        success: (result) => {
            let counter = 0;
            for (const [key, value] of Object.entries(result)) {
                inputs[counter].value = value;
                counter++;
            }
            
        }
    });
}

// function getCartItem() {
//     let table = document.querySelector('.tbl-detail .table-body');

//     $.ajax({
//         url: 'server/display_cart.php',
//         type: 'GET',
//         data: {request: 'request_page'},
//         success: (result) => {
//             table.innerHTML = result;
//         }
//     });
// }

function getRandomInt(max) {
    return Math.floor(Math.random() * max);
}

function makeRequest(reqPurpose, uploadedFiles, location) {


    const pLoadingMessage = document.getElementById("id-overlay-message");
    const overlay = document.getElementById('id-overlay');

    const message = [
        "Initializing quantum flux... preparing for digital transcendence.",
        "Activating cybernetic conduits... channeling the essence of the digital realm.",
        "Energizing the digital matrix... syncing with the heartbeat of the internet.",
        "Navigating the digital cosmos... surfing the binary tides.",
        "Connecting with the virtual ether... diving into the sea of code."
    ];

    pLoadingMessage.innerHTML= message[getRandomInt(5)];

    document.body.scrollTop = 0; // For Safari
    document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
    document.body.classList.add('overflow-hidden');
    overlay.classList.remove('hidden');


    const formData = new FormData();
    formData.append('reqPurpose', reqPurpose);
    formData.append('location', location);

    for (let i = 0; i < uploadedFiles.length; i++) {
        let fileName = 'uploadedFiles' + i;
        formData.append(fileName, uploadedFiles[i]);
    }

    formData.append('totalDoc', uploadedFiles.length);
    
    $.ajax({
        url: 'server/db_make_request.php',
        type: 'POST',
        data: formData,
        contentType: false, 
        processData: false,
        // dataType: 'json',
        success: (result) => {
            let returnCode = result.returnCode;
            console.log(result);

            if (returnCode == 200) {
                document.body.classList.remove('overflow-hidden');
                overlay.classList.add('hidden');
                $('#id-request-done').modal('show');
               
            } else if (returnCode == 404) {
                // window.location.href = 'index.php';
                console.log('Cart is empty!');
            }
            
        }
    });
}

function uploadFile() {
    $.ajax({
        url: 'server/db_upload_doc.php',
        type: 'POST',
        data : {},
        success: (result) => {
            // console.log(result);
        }
    });
}

// BUBBLING ON ONCHANGE METHOD \/\/\/\/\/\/\/


// function checkFile(e) {
//     console.log(e);
//     let file = e.target.files;
//     let xMark = document.createElement('i');
//     let parentDiv = e.target.parentElement;
    
//     if (file.length == 1) {
        
//         // Append button X to original input
        
//         xMark.className = 'bi bi-x';
//         xMark.setAttribute('onclick', 'removeInputField(event)');
//         parentDiv.appendChild(xMark);
//         // End button X to original input
        
//     } else {
//         let removeXMark = parentDiv.querySelector('.bi-x');
//         removeXMark.remove();
//     }
// }

function validateFileInput(e) {
    let file = e.target.files;

    if (file.length == 1) {
        uploadedFile.forEach(file => {
            file.classList.remove('error-input');
        })
        docErrorMsg.style.display = 'none';
    }
}

function removeInputField(e) {
    let parentDiv = e.target.parentElement;
    
    if (filesCounter != 1) {
        parentDiv.remove();
        filesCounter--;

        if (filesCounter != 5) {
            pAddDoc.style.display = 'block';
            pMaxDoc.style.display = 'none';
        }
    }
    
    
}

// function adjustGroup(e) {
//     let allSelectInput = document.querySelectorAll('.input-group .form-select');
//     let selectedValue = e.target.value;
//     let selectIndex = 0;
//     let inGroup = true;

//     // if (allSelectInput.length == 1) {
//     //     for (let i = 0; i < totalGroup.length; i++) {
//     //         let value = totalGroup[i].value;
            
//     //         if (value == selectedValue && selectedValue != 0) {
//     //             totalGroup.splice(i, 1);
//     //             return;
//     //         }
//     //     }
//     // } else {
//     //     allSelectInput.forEach((input) => {
//     //         if (e.target !== input) {
//     //             for (let i = 0; i < input.options.length; i++) {
//     //                 if (input.options[i].value == selectedValue) {
//     //                     input.options[i].remove();
//     //                     selectIndex = i;
//     //                 }
//     //             }
//     //         }
//     //         console.log(selectIndex);
//     //     })
//     // }

//     selectedGroup.forEach((group) => {
//         if (group == selectedValue) {
//             inGroup = false;
//             return;
//         }
//     })

//     if (inGroup) {
//         selectedGroup.push(selectedValue);
//     }
    
//     console.log(selectedGroup);

// }