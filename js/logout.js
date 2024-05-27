let logoutBtn = document.querySelector('.btn-logout');

logoutBtn.addEventListener('click', () => {
    $.ajax({
        type: 'POST',
        url: 'server/logout.php',
        success: (result) => {
            window.location.replace("login_page.php");
        }
    });
})