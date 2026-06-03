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

    const selectedDate =
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
            <div class="datu-rinda">
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
const loginBtn =
    document.getElementById("btn-login");

const loginModal =
    document.getElementById("login-modal");

loginBtn.addEventListener("click", () => {

    loginModal.classList.add("show-modal");

});

//uzspiezot arpus lauka pazudis tas
if(loginBtn && loginModal){

    loginBtn.addEventListener("click", () => {

        loginModal.classList.add("show-modal");

    });

    loginModal.addEventListener("click", (event) => {

        if(event.target === loginModal){

            loginModal.classList.remove("show-modal");

        }

    });

}
//uz leju iet lai varetu actually log in veikt
document.getElementById("btn-submit-login")
.addEventListener("click", () => {

    const email =
        document.getElementById("login-epasts").value;

    const password =
        document.getElementById("login-password").value;

    const formData = new FormData();

    formData.append("email", email);
    formData.append("password", password);

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
});
