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


    fetch("../API/kabineti.php").then(response=>response.json()).then(rooms => { //fetcho API lai rādītos no DB info par kabinetiem (pagaidām tikai room name un id ir)
        let html="";

        rooms.forEach(room =>{
            html += `
            <div class="datu-rinda">
            <div class="data-box">Kabinets Nr: ${room.name}</div>
            <div class="data-box">Vietas kabinetā:</div>
            <div class="data-box">Lietotājs</div>
            <div class="data-box">Paņēma:</div>
            <div class="status-ind green">
                 <span class="status-txt">Pieejams</span>
            </div>
            <div class="data-box">Nodeva:</div>
            </div>
            `;
        });
        document.getElementById("datu-kaste").innerHTML = html;
    })
    // for (let i = 0; i < 15; i++) {

    //     html += `
    //     <div class="datu-rinda">
    //         <div class="data-box">Kabinets Nr: ${i + 1}</div>
    //         <div class="data-box">Vietas kabinetā:</div>
    //         <div class="data-box">Lietotājs</div>
    //         <div class="data-box">Paņēma:</div>
    //         <div class="status-ind green">
    //             <span class="status-txt">Pieejams</span>
    //         </div>
    //         <div class="data-box">Nodeva:</div>
    //     </div>
    //     `;
    // }

document.getElementById("datu-kaste").innerHTML = html;

// meklet
const copy = document.getElementById("datu-kaste");
copy.innerHTML = html;

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
    document.getElementById("datu-kaste").innerHTML = html;
}

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