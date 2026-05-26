function openPopup(id){
    document.getElementById("room_id").value = id;

    const del = document.getElementById("delete_room_id");
    if(del) del.value = id;

    document.getElementById("overlay").style.display = "flex";
}

function closePopup(){
    document.getElementById("overlay").style.display = "none";
}



document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("room_name");
    const btn = document.getElementById("add_btn");

    if(input && btn){
        input.addEventListener("input", () => {
            btn.disabled = input.value.trim() === "";
        });
    }
});






const email = document.getElementById("email");
const password = document.getElementById("password");
const btn = document.getElementById("registerBtn");

// requirement elements
const len = document.getElementById("len");
const upper = document.getElementById("upper");
const num = document.getElementById("num");
const sym = document.getElementById("sym");

function validate() {
    const pass = password.value;

    const hasLength = pass.length >= 8;
    const hasUpper = /[A-Z]/.test(pass);
    const hasNumber = /\d/.test(pass);
    const hasSymbol = /[\W_]/.test(pass);
    const validEmail = email.value.includes("@");

    // update UI
    len.className = hasLength ? "valid" : "invalid";
    upper.className = hasUpper ? "valid" : "invalid";
    num.className = hasNumber ? "valid" : "invalid";
    sym.className = hasSymbol ? "valid" : "invalid";

    // enable button only if ALL valid
    btn.disabled = !(hasLength && hasUpper && hasNumber && hasSymbol && validEmail);
}

// run on input
email.addEventListener("input", validate);
password.addEventListener("input", validate);