const sodien = new Date();
const datumi = document.querySelector(".date")

datumi.textContent= sodien.getDate()+"."+(sodien.getMonth()+1)+"."+sodien.getFullYear();

const dienas=["Pirmdiena","Otrdiena","Trešdiena","Ceturtdiena","Piektdiena"];
let html="";

const d = Number(document.body.dataset.day);
let datums = new Date(sodien);
datums.setDate(sodien.getDate()+d);

const dienaDiv = document.querySelector(".diena");

dienaDiv.textContent =
    `${dienas[datums.getDay()]} (${datums.getDate()}.${datums.getMonth()+1}.${datums.getFullYear()})`;

// funkcija kas lauj bultinam iet uz prieku atpakalu 
const lapas = [
    "FrontP.html",
    "FrontP2.html",
    "FrontP3.html",
    "FrontP4.html",
    "FrontP5.html"
];

const currentDay = Number(document.body.dataset.day);

document.querySelector(".next")?.addEventListener("click", () => {
    if (currentDay < 4) {
        window.location.href = lapas[currentDay + 1];
    }
});

document.querySelector(".prev")?.addEventListener("click", () => {
    if (currentDay > 0) {
        window.location.href = lapas[currentDay - 1];
    }
});

for (let i = 0; i < 15; i++) {
    
    html += `
        <div class="datu-rinda">
            <div class="data-box">Kabinets Nr: ${i + 1}</div>
            <div class ="data-box"> Vietas kabinetā: </div>
            <div class="data-box">Lietotājs</div>
            <div class="data-box">Paņēma: </div>
            <div class="status-ind green">
                <span class="status-txt">Pieejams</span>
            </div>
            <div class="data-box">Nodeva: </div>
        </div>
    `;
    }

document.getElementById("datu-kaste").innerHTML = html;