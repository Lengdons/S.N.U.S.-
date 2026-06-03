let currentatslegaId = null;
let currentBooked = [];

function openPopup(id){
    //console.log(currentBooked);

    currentatslegaId = id;

    document.getElementById("atslega_id").value = id;

    document.getElementById("overlay").style.display = "flex";


    const del = document.getElementById("delete_atslega_id");
    if(del) del.value = id;

    loadraksti().then(() => {
        updateSlots(); //currentBooked
        setDefaultEndTime();
    });

    const startSelect = document.querySelector('select[name="start_laiks"]');
    const endSelect = document.querySelector('select[name="beigu_laiks"]');

    if (startSelect && endSelect) {

        const startIndex = startSelect.selectedIndex;

        // default: +1 slot (30 min)
        const endIndex = Math.min(startIndex + 1, endSelect.options.length - 1);

        endSelect.selectedIndex = endIndex;
    }
    
}

function loadraksti(){

    const atslegaId = document.getElementById("atslega_id").value;
    const date = document.getElementById("start_date").value;

    return fetch(`../aiznemtie_laiki.php?atslega_id=${currentatslegaId}&date=${document.getElementById("start_date").value}`)
        .then(response => response.json())
        .then(data => {
            

            currentBooked = data;
            //console.log(currentBooked)
            updateSlots();
    });
}

function setDefaultEndTime(){

    const startSelect = document.querySelector("select[name='start_laiks']");
    const endSelect = document.querySelector("select[name='beigu_laiks']");

    if (!startSelect || !endSelect) return;

    const startIndex = startSelect.selectedIndex;

    const endIndex = Math.min(startIndex + 1, endSelect.options.length - 1);

    endSelect.selectedIndex = endIndex;
}

function closePopup(){
    document.getElementById("overlay").style.display = "none";
}

document.addEventListener("DOMContentLoaded", () => {

    const startDate = document.getElementById("start_date");
    const endDate = document.getElementById("end_date");

    if(startDate && endDate){
        startDate.addEventListener("change", () => {
            if(endDate.value < startDate.value){
            endDate.value = startDate.value;
            }

            endDate.value = startDate.value;

           loadraksti().then(() => {
                updateSlots();
                setDefaultEndTime();
           });
        });
    }
})

document.addEventListener("DOMContentLoaded", () => {
    const startSelect = document.querySelector("select[name='start_laiks']");
    const endSelect = document.querySelector("select[name='beigu_laiks']");

    if (!startSelect || !endSelect) return;

    startSelect.addEventListener("change", () => {
        const startValue = startSelect.value;

        const [h, m] = startValue.split(":").map(Number);

        let date = new Date();
        date.setHours(h);
        date.setMinutes(m + 30);

        let newH = String(date.getHours()).padStart(2, "0");
        let newM = String(date.getMinutes()).padStart(2, "0");

        const newEnd = `${newH}:${newM}`;

        // set if exists in dropdown
        const exists = [...endSelect.options].some(o => o.value === newEnd);

        if (exists) {
            endSelect.value = newEnd;
        }else{
            setDefaultEndTime();
        }
    });
});

function updateSlots(){
    const selects = document.querySelectorAll("select[name='start_laiks'], select[name='beigu_laiks']");
    const selectedDate = document.getElementById("start_date")?.value;
    const today = new Date().toISOString().split('T')[0];
    const now = new Date();
    const currentMinutes = now.getHours()*60+now.getMinutes();

    const startSelect = document.querySelector("select[name='start_laiks']");
    const startValue = startSelect?.value;

    selects.forEach(select => {
        [...select.options].forEach(opt => {

            const val = String(opt.value).trim();

            let disabled = false;

            const [h,m] = val.split(':');
            const optionMinutes = parseInt(h)*60+parseInt(m);

            if(select.name === "start_laiks"){
            if(currentBooked.includes(val)){
                disabled = true;
                }
            }

            if(select.name === "beigu_laiks" && startValue){
                const [sh, sm] = startValue.split(":");
                const startMinutes = parseInt(sh)*60 + parseInt(sm);

                if(optionMinutes <= startMinutes){
                    disabled = true;
                }
            }

            if(selectedDate === today){
                if(currentMinutes>optionMinutes+15){
                    disabled = true;
                }
            }

            opt.disabled = disabled;

            if(disabled){
                opt.style.background = "#ddd";
                opt.style.color = "#888";
            } else {
                opt.disabled = false;
                opt.style.background = "";
                opt.style.color = "";
            }

        });

        if (select.name === "start_laiks") {
            selectFirstAvailable(select);
        }

    });
    setDefaultEndTime();
}

function selectFirstAvailable(select){

    for(const option of select.options){

        if(!option.disabled){

            select.value = option.value;
            return;
        }
    }
}



document.addEventListener("DOMContentLoaded", () => {
    const input = document.getElementById("atslega_nosaukums");
    const btn = document.getElementById("add_btn");

    if(input && btn){
        input.addEventListener("input", () => {
            btn.disabled = input.value.trim() === "";
        });
    }
});



function confirmDelete(){
    return confirm("Are you sure you want to delete this atslega?");
}


document.addEventListener("DOMContentLoaded", () => {

    const epasts = document.getElementById("epasts");
    const parole = document.getElementById("parole");
    const btn = document.getElementById("registretiesBtn");

    // ja nav ievadijis inputus un nav nospiesta poga tad apstajas
    if(!epasts || !parole || !btn) return;

    // requirement elements
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

        len.className = hasLength ? "valid" : "invalid";
        upper.className = hasUpper ? "valid" : "invalid";
        num.className = hasNumber ? "valid" : "invalid";
        sym.className = hasSymbol ? "valid" : "invalid";

        btn.disabled = !(hasLength && hasUpper && hasNumber && hasSymbol && validepasts);
    }

    epasts.addEventListener("input", validate);
    parole.addEventListener("input", validate);

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

    const nameInput = profileOverlay.querySelector("input[name='vards']");
    const uzvardsInput = profileOverlay.querySelector("input[name='uzvards']");
    const saveBtn = profileOverlay.querySelector("button[name='save_profile']");

    if (!nameInput || !uzvardsInput || !saveBtn) return;

    function validateProfile() {
        const valid =
            nameInput.value.trim() !== "" &&
            uzvardsInput.value.trim() !== "";

        saveBtn.disabled = !valid;
    }

    nameInput.addEventListener("input", validateProfile);
    uzvardsInput.addEventListener("input", validateProfile);

    // run once on load (important)
    validateProfile();
});

function openCreatelietotajsPopup(){
    document.getElementById("createlietotajsOverlay").style.display = "flex";
}

function closeCreatelietotajsPopup(){
    document.getElementById("createlietotajsOverlay").style.display = "none";
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