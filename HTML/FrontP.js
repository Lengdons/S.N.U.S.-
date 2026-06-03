const dienas = [
    "Pirmdiena",
    "Otrdiena",
    "Trešdiena",
    "Ceturtdiena",
    "Piektdiena"
];

let currentDay = 0;
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
            const aiznemts = room.user !== null && room.user !== "" && room.user !== undefined; // lai pareizi paraditu vai pieejams vai ne
            html += `
            <div class="datu-rinda">
            <div class="data-box">${room.name}</div>
            <div class="data-box">Vietas kabinetā:</div>
            <div class="data-box">${room.user ?? "Nav"}</div> 
            <div class="data-box">${room.start ?? "-"}</div>
            <div class="status-ind ${room.occupied ? "red" : "green"}">
                 <span class="status-txt">${room.occupied ? "Aizņemts" : "Pieejams"}</span>
            </div>
            <div class="data-box">${room.end ?? "-"}</div>
            </div>
            `;
        });
        document.getElementById("datu-kaste").innerHTML = html;
    })
}

//pievienots kalendars, uzspiezot uz pogu atversies mini kalendars, bet pagaidam nekadu funkciju isti nedod iznemot vienkarsi atver kalendaru
const calendar = document.getElementById("calendar");

document.getElementById("btn-kalendars").addEventListener("click", () => {

    calendar.showPicker();

});