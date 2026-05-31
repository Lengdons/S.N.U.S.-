
const copy = document.getElementById('datu-kaste');

const P_kabinetuSkaits = 5; 
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
}


copy.innerHTML = PieejamsRindas + AiznemtsRindas;