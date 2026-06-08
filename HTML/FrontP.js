//globalie mainigie
let currentRoomId = null;
let currentBooked = [];
let selectedDate = null;



const dienas = [
    "Pirmdiena",
    "Otrdiena",
    "Trešdiena",
    "Ceturtdiena",
    "Piektdiena"
];

let weekday = new Date().getDay();

if (weekday === 0) {
    weekday = 7;
}

let currentDay = weekday - 1;
let currentWeek = 0;

function renderDay() {

    
    const sodien = new Date();

    const pirmdiena = new Date(sodien);

    let weekday = sodien.getDay(); 

    if (weekday === 0) { //saja rinda notiek cikls if, jo js uzskata svetdienu par 0 
        weekday = 7;
    }

    pirmdiena.setDate(sodien.getDate() - weekday + 1);  //aprekina sis nedelas pirmdienu

    const datums = new Date(pirmdiena); //aprekina konkreto dienu
    datums.setDate(
        pirmdiena.getDate() + currentDay + currentWeek * 7
    );

    document.querySelector(".diena").textContent =
        `${dienas[currentDay]} (${datums.getDate()}.${datums.getMonth()+1}.${datums.getFullYear()})`;


    document.querySelector(".date").textContent =
        `${sodien.getDate()}.${sodien.getMonth() + 1}.${sodien.getFullYear()}`;

    selectedDate =
    datums.getFullYear() + "-" +
    String(datums.getMonth() + 1).padStart(2, '0') + "-" +
    String(datums.getDate()).padStart(2, '0');

    loadRooms(selectedDate);
}

// meklet

const searchInput = document.getElementById("searchInput");

searchInput.addEventListener("input", () => {

    const search = searchInput.value.toLowerCase();

    document.querySelectorAll(".datu-rinda").forEach(rinda => {

        if (rinda.textContent.toLowerCase().includes(search)) {
            rinda.style.display = "";
        } else {
            rinda.style.display = "none";
        }

    });

});


renderDay();
// funkcija kas lauj bultinam iet uz prieku atpakalu 
document.querySelector(".next").addEventListener("click", () => {

    currentDay++;

    if (currentDay > 4) {
        currentDay = 0;
        currentWeek++;
    }

    renderDay();
});

document.querySelector(".prev").addEventListener("click", () => {

    currentDay--;

    if (currentDay < 0) {
        currentDay = 4;
        currentWeek--;
    }

    renderDay();
});
// viss ^^^ lauj darīt tā, ka nav javeido atseviski 5 html{php} lapas bet iet ar vienu
function loadRooms(date){
    fetch(`../API/kabineti.php?date=${date}`).then(response=> response.json()).then(rooms=>{ //seit ir date klat lai varetu izdarit ta ka ejot uz nakamo dienu radisies tas dienas rezervacijas un kab
        let html="";
        
        rooms.forEach(room=>{
            const aiznemts = room.lietotajs !== null && room.lietotajs !== "" && room.lietotajs !== undefined; // lai pareizi paraditu vai pieejams vai ne
            let statusClass = "";
            switch(room.status){

                case "Pieejams":
                    statusClass = "green";
                    break;

                case "Aizņemts":
                    statusClass = "red";
                    break;

                case "Rezervēts":
                    statusClass = "red";
                    break;

                case "Nodots":
                    statusClass = "green";
                    break;}
            html += `
            <div class="datu-rinda" onclick="openRezerve(${room.id})">
            <div class="data-box">${room.nosaukums}</div>
            <div class="data-box">Vietas kabinetā:</div>
            <div class="data-box">${room.lietotajs ?? "Nav"}</div> 
            <div class="data-box">${room.start ?? "-"}</div>
            <div class="status-ind ${statusClass}">
                 <span class="status-txt">${room.status}</span>
            </div>
            <div class="data-box">${room.end ?? "-"}</div>
            </div>
            `;
        });
        document.getElementById("datu-kaste").innerHTML = html;
    })
}

//pievienots kalendars, uzspiezot uz pogu atversies mini kalendars, bet pagaidam nekadu funkciju isti nedod iznemot vienkarsi atver kalendarus
const fp = flatpickr("#calendar", {
    positionElement: document.getElementById("btn-kalendars"), 
    monthSelectorType: "static"
});
document.getElementById("btn-kalendars").addEventListener("click", () => {
    fp.open();
});

//talak uz leju iet viss login lapai
const loginBtn = document.getElementById("btn-login");
const loginModal = document.getElementById("login-modal");

//uzspiezot arpus lauka pazudis tas
if(loginBtn && loginModal){

    loginBtn.addEventListener("click", () => {

        loginModal.classList.add("show-modal");

    });

    // loginModal.addEventListener("click", (event) => {

    //     if(event.target === loginModal){

    //         loginModal.classList.remove("show-modal");

    //     }

    // });

}
//uz leju iet lai varetu actually log in veikt
function login(){

    const email =
        document.getElementById("login-epasts").value;

    const password =
        document.getElementById("login-password").value;

    const formData = new FormData();

    formData.append("epasts", email);
    formData.append("parole", password);

    fetch("../API/Pieraksities.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {

        if(data.status === "success"){
            location.reload();
        }
        else{
            document.getElementById("login-error-msg").textContent = data.message;
        }
    });
};
document.addEventListener("DOMContentLoaded", () => {

    const isLoggedIn =
        document.body.dataset.loggedIn === "true";

    const loginModal =
        document.getElementById("login-modal");

    if (!isLoggedIn) {
        loginModal.classList.add("show-modal");
        document.body.style.overflow = "hidden";
    }
});

const logoutBtn = document.getElementById("btn-logout");

console.log(logoutBtn);

if(logoutBtn){

    logoutBtn.addEventListener("click", () => {


        window.location.href = "../API/Izrakstities.php";

    });

}

const submitBtn =
    document.getElementById("btn-submit-login");

if(submitBtn){
    submitBtn.addEventListener("click", login);
}

const loginForm =
    document.getElementById("login-form");

if(loginForm){
    loginForm.addEventListener("submit", function(e){
        e.preventDefault();
        login();
    });
}

//koda fragments kas parbauda vai lietotajam ir vards un uzvards un ja nav tad izmet popup un liek lietotajam ievadit vardu un uzvardu
document.addEventListener("DOMContentLoaded", () => {

    const vajagProfile = document.body.dataset.vajagProfile === "true";
    const profileModal = document.getElementById("profile-modal");
    
    if (vajagProfile && profileModal) {
        profileModal.classList.add("show-modal");
        document.body.style.overflow = "hidden";
    }

    const btn = document.getElementById("save-profile-btn");
    if(!btn) return;

    btn.addEventListener("click", () => {
        const nosaukums = document.getElementById("prof-nosaukums").value.trim();
        const uzvards = document.getElementById("prof-uzvards").value.trim();

        if(!nosaukums || !uzvards){
            alert("Aizpildi visus laukus");
            return;
        }

        const formData = new FormData();
        formData.append("nosaukums", nosaukums);
        formData.append("uzvards", uzvards);

        fetch("../API/saglabat_profile.php", {
            method: "POST",
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if(data.status === "success"){
                location.reload();
            } else {
                alert(data.message);
            }
        });
    });
});

//funkcija kas lauj lietotajam rezervet istabas
function openRezerve(roomId){

    const loggedIn =
        document.body.dataset.loggedIn === "true";

    if(!loggedIn){
        alert("Vispirms pieslēdzieties");
        return;
    }

    currentRoomId = roomId;

    document.getElementById("rez-kabinets-id").value =
        roomId;

    document.getElementById("start_date").value =
        selectedDate;

    const endDate =
        document.getElementById("end_date");

    if(endDate){
        endDate.value = selectedDate;
    }

    document.getElementById("rezervet-modal")
        .classList.add("show-modal");

    loadBookedTimes().then(() => {

        updateSlots();
        const startSelect = document.getElementById("start_laiks");
        const endSelect = document.getElementById("beigu_laiks");

        selectFirstAvailable(startSelect);
        setDefaultEndTime();

    });
}

function setDefaultEndTime(){

    const startSelect =
        document.getElementById("start_laiks");

    const endSelect =
        document.getElementById("beigu_laiks");

    if(!startSelect || !endSelect) return;

    const startIndex = startSelect.selectedIndex;

    const endIndex =
        Math.min(
            startIndex + 1,
            endSelect.options.length - 1
        );

    endSelect.selectedIndex = endIndex;
}

function selectFirstAvailable(select){

    for(const option of select.options){

        if(!option.disabled){

            select.value = option.value;
            return;
        }
    }
}

//funkcija kas panem esosam kabinetam id  un sakuma datumu aizmet prom uz aiznemtie laiki un panem datus no aiznemtie_laiki.php
function loadBookedTimes(){

    return fetch(
        `../API/aiznemtie_laiki.php?atslega_id=${currentRoomId}&date=${document.getElementById("start_date").value}`
    )
    .then(r => r.json())
    .then(data => {

        currentBooked = data;

    });
}


//rezervet poga
document.getElementById("btn-rezervet")
.addEventListener("click", () => {

    const roomId =
        document.getElementById("rez-kabinets-id").value;

    const formData = new FormData();

    formData.append("atslega_id", roomId);

    const userSelect =
    document.getElementById("book_lietotajs_id");

    if(userSelect){

        formData.append(
            "book_lietotajs_id",
            userSelect.value
        );

    }

    formData.append(
        "start_date",
        document.getElementById("start_date").value
    );

    formData.append(
        "end_date",
        document.getElementById("end_date").value
    );

    formData.append(
        "start_laiks",
        document.getElementById("start_laiks").value
    );

    formData.append(
        "beigu_laiks",
        document.getElementById("beigu_laiks").value
    );

    fetch("../API/rezervet_kabinetu.php", {
        method: "POST",
        body: formData
    })
    .then(r => r.json())
    .then(data => {

        alert(data.message);

        if(data.status === "success"){

            document.getElementById(
                "rezervet-modal"
            ).classList.remove("show-modal");

            loadRooms(selectedDate);
        }
    });
});

//atjaunot pielajamas vietas prieks start laika un beigu laika, ta kad nomaina datumu tiek atjaunots ari laiks
function updateSlots(){ 

    const startSelect =
        document.getElementById("start_laiks");

    const endSelect =
        document.getElementById("beigu_laiks");

    const selectedDate =
        document.getElementById("start_date").value;

    if (!startSelect || !endSelect) return;

    const today =
        new Date().toISOString().split("T")[0];

    const now = new Date();

    const currentMinutes =
        now.getHours()*60 + now.getMinutes();

    const isPastDate = selectedDate < today;
    const startValue = startSelect?.value;

    // reset state (IMPORTANT — this is what fixes your bug)
    startSelect.disabled = false;
    endSelect.disabled = false;

     // reset selections ONLY when changing day
    const previousStart = startSelect.value;
    const previousEnd = endSelect.value;

    [startSelect, endSelect].forEach(select => {

        [...select.options].forEach(opt => {

            let disabled = false;

            if (isPastDate) {
                disabled = true; // disable visas opcijas lai nevaretu book vecus datus
            }

            const [h,m] = opt.value.split(":");

            const optionMinutes =
                parseInt(h)*60 + parseInt(m);

            if(select.id === "start_laiks"){

                if(currentBooked.includes(opt.value)){
                    disabled = true;
                }
            }

            if(
                select.id === "beigu_laiks" &&
                startValue
            ){

                const [sh,sm] =
                    startValue.split(":");

                const startMinutes =
                    parseInt(sh)*60 +
                    parseInt(sm);

                if(optionMinutes <= startMinutes){
                    disabled = true;
                }
            }

            if(selectedDate === today){

                if(currentMinutes > optionMinutes + 15){
                    disabled = true;
                }
            }

            opt.disabled = disabled;

            if(disabled){

                opt.style.background = "#ddd";
                opt.style.color = "#888";

            }else{

                opt.style.background = "";
                opt.style.color = "";
            }

        });

    });
    // restore previous selection if still valid
if ([...startSelect.options].some(o => o.value === previousStart && !o.disabled)) {
    startSelect.value = previousStart;
} else {
    selectFirstAvailable(startSelect);
}

if ([...endSelect.options].some(o => o.value === previousEnd && !o.disabled)) {
    endSelect.value = previousEnd;
} else {
    selectFirstAvailable(endSelect);
}
    // after all options are processed

    // ensure end is always aligned AFTER start is finalized
    setDefaultEndTime();
}

//kodu fragments kas dod iespeju kad lietotajs izveleas sakuma laiku. tad automatiski beigu laiks ir +30 min
document.addEventListener("DOMContentLoaded", () => {

    const startSelect =
        document.getElementById("start_laiks");

    const endSelect =
        document.getElementById("beigu_laiks");

    if(!startSelect || !endSelect) return;

    startSelect.addEventListener("change", () => {

        const startValue = startSelect.value;

        const [h,m] =
            startValue.split(":").map(Number);

        let date = new Date();

        date.setHours(h);
        date.setMinutes(m + 30);

        const newH =
            String(date.getHours()).padStart(2,"0");

        const newM =
            String(date.getMinutes()).padStart(2,"0");

        const newEnd = `${newH}:${newM}`;

        const exists =
            [...endSelect.options]
            .some(o => o.value === newEnd);

        if(exists){
            endSelect.value = newEnd;
        }
        else{
            setDefaultEndTime();
        }

        updateSlots();
    });
});

//koda fragments kas atjauno laiku izveles intervalus, kad tiek nomainits datums

document.addEventListener("DOMContentLoaded", () => {

    const startDate =
        document.getElementById("start_date");

    const endDate =
        document.getElementById("end_date");

    if(!startDate) return;

    startDate.addEventListener("change", () => {

        if(endDate){

            if(endDate.value < startDate.value){
                endDate.value = startDate.value;
            }

            endDate.value = startDate.value;
        }

        loadBookedTimes().then(() => {

            updateSlots();

        });

    });

});

//koda fragments lai aizvertu ciet rezerves logu


const modals = document.querySelectorAll(".modal-parklajums");

modals.forEach(modal => {

    modal.addEventListener("click", function(e){

        if(
            e.target === modal &&
            modal.id !== "login-modal"
        ){
            modal.classList.remove("show-modal");
        }

    });

});
// const closeRezBtn =
//     document.getElementById("btn-aizvert-rezervi");

// if(closeRezBtn){

//     closeRezBtn.addEventListener("click", () => {

//         document.getElementById("rezervet-modal")
//             .classList.remove("show-modal");

//     });

// }
const btnAdmin = document.getElementById("btn-admin");

if(btnAdmin){
    btnAdmin.addEventListener("click", () => {
        window.location.href = "Admin.php";
    });
}