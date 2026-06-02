
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
copy.innerHTML = PieejamsRindas + AiznemtsRindas;

const btnPieejams = document.getElementById('btn-filtrs-pieejams');
const btnAiznemts = document.getElementById('btn-filtrs-aiznemts');

btnPieejams.addEventListener('click', () => {

    btnPieejams.classList.add('filtrs-selected');

    btnAiznemts.classList.remove('filtrs-selected');
});
btnAiznemts.addEventListener('click', () => {

    btnAiznemts.classList.add('filtrs-selected');

    btnPieejams.classList.remove('filtrs-selected');
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