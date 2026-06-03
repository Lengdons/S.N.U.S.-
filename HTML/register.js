document.getElementById("btn-submit-register").addEventListener("click", async () => {

    const epasts = document.getElementById("reg-epasts").value;
    const parole = document.getElementById("reg-password").value;

    const formData = new FormData();

    formData.append("epasts", epasts);
    formData.append("parole", parole);

    try {

        const response = await fetch("../API/Registreties.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        alert(data.message);

        if (data.status === "success") {
            window.location.href = "FrontP.php";
        }

    } catch (error) {

        console.error(error);
        alert("Servera kļūda");

    }

});

document.addEventListener("DOMContentLoaded", () => {

    const epasts = document.getElementById("reg-epasts");
    const parole = document.getElementById("reg-password");
    const btn = document.getElementById("btn-submit-register");

    if(!epasts || !parole || !btn) return;

    const len = document.getElementById("len");
    const upper = document.getElementById("upper");
    const num = document.getElementById("num");
    const sym = document.getElementById("sym");

    const bubble = document.getElementById("paroleBubble");

    parole.addEventListener("focus", () => {
        bubble.style.display = "block";
    });

    parole.addEventListener("blur", () => {
        setTimeout(() => {
            bubble.style.display = "none";
        }, 200);
    });

    function validate() {

        const pass = parole.value;

        const hasLength = pass.length >= 8;
        const hasUpper = /[A-Z]/.test(pass);
        const hasNumber = /\d/.test(pass);
        const hasSymbol = /[\W_]/.test(pass);
        const validepasts = epasts.value.includes("@");

        len.className = hasLength ? "ja" : "ne";
        upper.className = hasUpper ? "ja" : "ne";
        num.className = hasNumber ? "ja" : "ne";
        sym.className = hasSymbol ? "ja" : "ne";

        btn.disabled = !(hasLength && hasUpper && hasNumber && hasSymbol && validepasts);
    }

    epasts.addEventListener("input", validate);
    parole.addEventListener("input", validate);

    validate();
});