let currentBooked = [];

function openPopup(id, booked){
    console.log(currentBooked);
    document.getElementById("room_id").value = id;
    currentBooked = booked || [];

    const del = document.getElementById("delete_room_id");
    if(del) del.value = id;

    document.getElementById("overlay").style.display = "flex";

    updateSlots();
}

function closePopup(){
    document.getElementById("overlay").style.display = "none";
}


function updateSlots(){
    const selects = document.querySelectorAll("select[name='start_time'], select[name='end_time']");

    selects.forEach(select => {
        [...select.options].forEach(opt => {

            const val = String(opt.value).trim();
            const booked = currentBooked.map(v => String(v).trim());

            if(booked.includes(val)){
                opt.disabled = true;
                opt.style.background = "#ddd";
                opt.style.color = "#888";
            } else {
                opt.disabled = false;
                opt.style.background = "";
                opt.style.color = "";
            }

        });
    });
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



function confirmDelete(){
    return confirm("Are you sure you want to delete this room?");
}


document.addEventListener("DOMContentLoaded", () => {

    const email = document.getElementById("email");
    const password = document.getElementById("password");
    const btn = document.getElementById("registerBtn");

    // stop if not on register page
    if(!email || !password || !btn) return;

    // requirement elements
    const len = document.getElementById("len");
    const upper = document.getElementById("upper");
    const num = document.getElementById("num");
    const sym = document.getElementById("sym");

    const bubble = document.getElementById("passwordBubble");

    password.addEventListener("focus", () => {
        bubble.style.display = "block";
    });

    password.addEventListener("blur", () => {
        setTimeout(() => {
            bubble.style.display = "none";
        }, 200);
    });

    function validate() {

        const pass = password.value;

        const hasLength = pass.length >= 8;
        const hasUpper = /[A-Z]/.test(pass);
        const hasNumber = /\d/.test(pass);
        const hasSymbol = /[\W_]/.test(pass);
        const validEmail = email.value.includes("@");

        len.className = hasLength ? "valid" : "invalid";
        upper.className = hasUpper ? "valid" : "invalid";
        num.className = hasNumber ? "valid" : "invalid";
        sym.className = hasSymbol ? "valid" : "invalid";

        btn.disabled = !(hasLength && hasUpper && hasNumber && hasSymbol && validEmail);
    }

    email.addEventListener("input", validate);
    password.addEventListener("input", validate);

});


document.addEventListener("DOMContentLoaded", () => {
    const app = document.getElementById("app");
    const needsProfile = app?.dataset.needsProfile === "1";

    if (needsProfile) {

         // disable page scrolling
        document.body.style.overflow = "hidden";

        window.profileLocked = needsProfile;

        const overlay = document.getElementById("profileOverlay");
        if (overlay) {
            overlay.style.display = "flex";
        }
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const profileOverlay = document.getElementById("profileOverlay");

    if (!profileOverlay) return;

    const nameInput = profileOverlay.querySelector("input[name='name']");
    const surnameInput = profileOverlay.querySelector("input[name='surname']");
    const saveBtn = profileOverlay.querySelector("button[name='save_profile']");

    if (!nameInput || !surnameInput || !saveBtn) return;

    function validateProfile() {
        const valid =
            nameInput.value.trim() !== "" &&
            surnameInput.value.trim() !== "";

        saveBtn.disabled = !valid;
    }

    nameInput.addEventListener("input", validateProfile);
    surnameInput.addEventListener("input", validateProfile);

    // run once on load (important)
    validateProfile();
});

function openCreateUserPopup(){
    document.getElementById("createUserOverlay").style.display = "flex";
}

function closeCreateUserPopup(){
    document.getElementById("createUserOverlay").style.display = "none";
}

function toggleRow(row, inputName){
    const input = row.querySelector(`input[name='${inputName}']`);

    const selected = row.classList.toggle("selected");

    input.disabled = !selected;
}

document.addEventListener("DOMContentLoaded", () => {
    const input = document.querySelector("input[name='duration_days']");

    if (!input) return;

    input.addEventListener("input", () => {
        let val = parseInt(input.value);

        if (isNaN(val)) return;

        if (val > 365) val = 365;
        if (val < 1) val = 1;

        input.value = val;
    });
});