
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

document.getElementById("btn-filtrs-apply")
.addEventListener("click", () => {

    const showAvailable =
        btnPieejams.classList.contains("filtrs-selected");

    const showOccupied =
        btnAiznemts.classList.contains("filtrs-selected");

    document.querySelectorAll(".datu-rinda")
    .forEach(rinda => {

        const status =
            rinda.querySelector(".status-txt").textContent;

        if(showAvailable && status === "Pieejams"){
            rinda.style.display = "";
        }
        else if(showOccupied && status === "Aizņemts"){
            rinda.style.display = "";
        }
        else{
            rinda.style.display = "none";
        }

    });

});
// -----------------------------------------------------------------------------------------------

// Atver un aizver filtra lodziņu \/ \/ \/ \/ \/
const filtrsBtn = document.getElementById('btn-filtrs');
const filtrsModal = document.getElementById('filtrs-modal');
const closeBtn = document.getElementById('btn-close-modal');

filtrsBtn.addEventListener('click', () => {
    filtrsModal.classList.add('show-modal');
});

closeBtn.addEventListener('click', () => {
    filtrsModal.classList.remove('show-modal');
});

filtrsModal.addEventListener('click', (event) => {
    if (event.target === filtrsModal) {
        filtrsModal.classList.remove('show-modal');
    }
});
//                ^^^^^^^^ Filtra logam