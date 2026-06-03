
/*const copy = document.getElementById('datu-kaste');

const P_kabinetuSkaits = 50; 
const A_kabinetuSkaits = 3;

let PieejamsRindas = '';

for (let i = 0; i < P_kabinetuSkaits; i++) {
    
    PieejamsRindas += `
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

let AiznemtsRindas = '';

for (let i = 0; i < A_kabinetuSkaits; i++) {
    
    AiznemtsRindas += `
        <div class="datu-rinda">
            <div class="data-box">Kabinets Nr: ${i + 1}</div>
            <div class ="data-box"> Vietas kabinetā: </div>
            <div class="data-box">Lietotājs</div>
            <div class="data-box">Paņēma: </div>
            <div class="status-ind red">
                <span class="status-txt">Aizņemts</span>
            </div>
            <div class="data-box">Nodeva: </div>
        </div>
    `;
}*/

// selecto filtra opcijas: Pieejams / Aiznemts ---------------------------------------------------
// document.getElementById("datu-kaste").innerHTML = PieejamsRindas + AiznemtsRindas;

// ---------------- FILTRA ELEMENTI ----------------

const btnPieejams =
    document.getElementById("btn-filtrs-pieejams");

const btnAiznemts =
    document.getElementById("btn-filtrs-aiznemts");

const filtrsModal =
    document.getElementById("filtrs-modal");

// ---------------- PIEEJAMS POGA ----------------

btnPieejams.addEventListener("click", () => {

    if(btnPieejams.classList.contains("filtrs-selected")){
        btnPieejams.classList.remove("filtrs-selected");
    }
    else{
        btnPieejams.classList.add("filtrs-selected");
        btnAiznemts.classList.remove("filtrs-selected");
    }

});

// ---------------- AIZŅEMTS POGA ----------------

btnAiznemts.addEventListener("click", () => {

    if(btnAiznemts.classList.contains("filtrs-selected")){
        btnAiznemts.classList.remove("filtrs-selected");
    }
    else{
        btnAiznemts.classList.add("filtrs-selected");
        btnPieejams.classList.remove("filtrs-selected");
    }
});


// ---------------- PIELIETOT FILTRU ----------------

document.getElementById("btn-filtrs-apply")
.addEventListener("click", () => {

    const showAvailable =
        btnPieejams.classList.contains("filtrs-selected");

    const showOccupied =
        btnAiznemts.classList.contains("filtrs-selected");

    const cabinetNumber =
        document.getElementById("filtrs-kabinets")
        .value
        .trim()
        .toLowerCase();

    document.querySelectorAll(".datu-rinda")
    .forEach(rinda => {

        const status =
            rinda.querySelector(".status-txt")
            .textContent
            .trim();

        const roomName =
            rinda.querySelector(".data-box")
            .textContent
            .trim()
            .toLowerCase();

        let statusMatch = true;
        let roomMatch = true;

        // Statusa filtrs
        if(showAvailable){
            statusMatch = (status === "Pieejams");
        }
        else if(showOccupied){
            statusMatch =
                (status === "Aizņemts" ||
                 status === "Rezervēts");
        }

        // Kabineta numura filtrs
        if(cabinetNumber !== ""){
            roomMatch =
                roomName.includes(cabinetNumber);
        }

        // Gala pārbaude
        if(statusMatch && roomMatch){
            rinda.style.display = "";
        }
        else{
            rinda.style.display = "none";}
    });
    filtrsModal.classList.remove("show-modal");

});
// ---------------- ATVĒRT / AIZVĒRT FILTRA LOGU ----------------

const filtrsBtn =
    document.getElementById("btn-filtrs");

const closeBtn =
    document.getElementById("btn-close-modal");

filtrsBtn.addEventListener("click", () => {
    filtrsModal.classList.add("show-modal");
});

closeBtn.addEventListener("click", () => {
    filtrsModal.classList.remove("show-modal");
});

filtrsModal.addEventListener("click", (event) => {

    if(event.target === filtrsModal){
        filtrsModal.classList.remove("show-modal");
    }

});