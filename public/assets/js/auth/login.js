document.addEventListener("DOMContentLoaded", function () {
    let email = document.querySelector("#email");
    let password = document.querySelector("#password");
    let btnLogin = document.querySelector("#btnLogin");
    let loginForm = document.querySelector("#loginForm");

    btnLogin.addEventListener("click", () => {
        if (!validateEmail(email.value)) {
            Swal.fire({
                title: "Uyari",
                text: "Lutfen gecerli bir email adresi girin",
                icon: "warning",
            });
        } else if (password.value.length < 8) {
            Swal.fire({
                title: "Uyari",
                text: "Parolaniz e az 8 karakterden olusmalidir.",
                icon: "warning",
            });
        } else {
            loginForm.submit();
        }
    });

    function validateEmail(email) {
        let regex =
            /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
});
