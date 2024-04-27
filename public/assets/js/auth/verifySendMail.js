document.addEventListener("DOMContentLoaded", function () {
    let email = document.querySelector("#email");
    let btnSendMail = document.querySelector("#btnSendMail");
    let verifyMailForm = document.querySelector("#verifyMailForm");

    btnSendMail.addEventListener("click", () => {
        if (!validateEmail(email.value.trim())) {
            Swal.fire({
                title: "Uyari",
                text: "Lutfen gecerli bir email adresi girin",
                icon: "warning",
            });
        } else {
            verifyMailForm.submit();
        }
    });

    function validateEmail(email) {
        let regex =
            /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
        return regex.test(email);
    }
});
