window.onload = () => {
    let email = document.getElementById('id-login-email');
    let pass = document.getElementById('id-login-password');
    let btnLogin = document.getElementById('id-btn-login');
    let btnPopup = document.getElementById('id-btn-close-popup');
    let emailErrMsg = document.querySelector('.email-error-msg');
    let passErrMsg = document.querySelector('.pass-error-msg');

    btnPopup.addEventListener('click', (e) => {
        $('.modal').modal('hide');
    });

    let keydownHandler = (e) => {
        console.log(e.target.nextElementSibling);
        e.target.classList.remove('error-input');
        e.target.nextElementSibling.style.display = 'none';
        e.target.removeEventListener('keydown', keydownHandler);
    }

    

    btnLogin.addEventListener('click', (e) => {
        if (email.value.trim() == '' || pass.value.trim() == '') {
            if (email.value.trim() == '' && pass.value.trim() == '') {
                email.classList.add('error-input');
                pass.classList.add('error-input');
                emailErrMsg.style.display = 'block';
                passErrMsg.style.display = 'block';
                email.addEventListener('keydown', keydownHandler);
                pass.addEventListener('keydown', keydownHandler);
            } else if (email.value.trim() == '') {
                email.classList.add('error-input');
                emailErrMsg.style.display = 'block';
                email.addEventListener('keydown', keydownHandler);
            } else {
                pass.classList.add('error-input');
                passErrMsg.style.display = 'block';
                pass.addEventListener('keydown', keydownHandler);
            }
            
        } else {
            
            $.ajax({
                type: 'POST',
                url: 'server/login.php',
                data: {
                    userEmail: email.value.trim(),
                    userPass: pass.value.trim()
                },
                dataType: 'json',
                success: (result) => {

                   let returnCode = result.returnCode;
                   console.log(result);

                    if (returnCode == 200) {
                        window.location.replace("index.php");
                        
                    } else if (returnCode == 201) {
                        window.location.replace('admin_dashboard.php');
                    } else if (returnCode == 404) {
                        $('#loginError').modal('show');
                        console.log(result);
                    } else {
                        
                        console.log(result);
                    }
                }
        
           });

           
        }
    });


}