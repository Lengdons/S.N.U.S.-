document.getElementById("btn-register")
.addEventListener("click", () => {

    const email =
        document.getElementById("register-email").value;

    const password =
        document.getElementById("register-password").value;

    const formData = new FormData();

    formData.append("email", email);
    formData.append("password", password);

    fetch("../API/registreties.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {

        alert(data.message);

        if(data.status === "success"){
            window.location.href = "FrontP.php";
        }

    });

});