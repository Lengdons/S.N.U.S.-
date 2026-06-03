function deleteRoom(id){

    const formData = new FormData();
    formData.append("room_id", id);

    fetch("../API/izdzest_kabinetu.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        //console.log(data);

        alert(data.message);

        location.reload();

    });

}

function loadKabineti() {
fetch("../API/kabineti.php").then(res => res.json()).then(rooms => {

    let html = "";

    rooms.forEach(room => {

        html += `
        <div class="list-item">
            <span>Kabinets Nr: ${room.nosaukums}</span>
            <button
                class="btn-sarkans"
                onclick="deleteRoom(${room.id})" 
            >
                Dzēst
            </button>
        </div>
        `;
    });
// lauj izdzest kabinetu
    document.getElementById("kabinetu-saraksts").innerHTML = html;
});
}

loadKabineti();

//pievienot kabinetu kas izmanto pievienot kabinetu api
//ar post palidzibu ievieto db jauno
function pievienotKabinetu() {
    const roomName = document.getElementById("jauns-kabinets-nosaukums").value;

    const formData = new FormData();
    formData.append("room_name",roomName);

    fetch("../API/pievienot_kabinetu.php", {

        method: "POST",
        body: formData

    })
    .then(response => response.json()).then(data => {
        //console.log(data);
        alert(data.message);

        if (data.status === "success") {
            document.getElementById("jauns-kabinets-nosaukums").value = "";
            loadKabineti(); //ja veiksmigi pievieno kabinetu, tad palaidz loadkabineti funkciju lai nav manuali refresh jataisa
        }
    });
};

//pogas funkcijas, kas lauj aiziet uz lietotaju sadalu un vestures sadalu

const navButtons= document.querySelectorAll(".admin-nav-btn");
const sections = document.querySelectorAll(".admin-zurnals");

navButtons.forEach(button => {
    button.addEventListener("click", () =>{
        navButtons.forEach(btn =>
            btn.classList.remove("active")
        );

        sections.forEach(section =>
            section.classList.remove("active")
        );

        button.classList.add("active");

        const target =
            button.dataset.target;

        document
            .getElementById(target)
            .classList.add("active");

    });
});

//api ar izveidot lietotaju, lietotaju sadala

document.getElementById("btn-pievienot-liet").addEventListener("click",()=>{
    const name =
        document.getElementById(
            "jauns-vards"
        ).value;
    const surname =
        document.getElementById(
            "jauns-uzvards"
        ).value;
    const email =
        document.getElementById(
            "jauns-epasts"
        ).value;
    const password =
        document.getElementById(
            "jauna-parole"
        ).value;
    const number =
        document.getElementById(
            "dienu-skaits"
        ).value;    
    const formData = new FormData();

    formData.append(
        formData.append("email", email),
        formData.append("password", password),
        formData.append("duration_days", number),
    );

    fetch("../API/pievienot_lietotaju.php", {

        method: "POST",
        body: formData

    })
    .then(response => response.json()).then(data => {
        alert(data.message);
    });
});

document.getElementById("btn-export-vesture").addEventListener("click", () => {

    const link = document.createElement("a");
    link.href = "../export/export_vesture.php";
    link.download = "zurnali.csv";

    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);

});

document.getElementById("btn-export-pieraksti")
.addEventListener("click", () => {

    window.location.href =
        "../export/export_pieraksti.php";

});


document.getElementById("btn-dzest-izvele-pieraksti")
.addEventListener("click", () => {

    const ids = [];

    document
        .querySelectorAll(".pieraksts-row.selected")
        .forEach(cb => {

            ids.push(cb.dataset.id);

        });

    if(ids.length === 0){
        alert("Nav izvēlēts neviens ieraksts");
        return;
    }

    fetch("../API/dzest_izveletos_pierakstus.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            ids: ids
        })
    })
    .then(r => r.json())
    .then(data => {

        if(data.status === "success"){
            location.reload();
        }

    });

});

document.getElementById("btn-dzest-izvele-vesture")
.addEventListener("click", () => {

    const ids = [];

    document
        .querySelectorAll(".vesture-row.selected")
        .forEach(cb => {

            ids.push(cb.dataset.id);

        });

    if(ids.length === 0){
        alert("Nav izvēlēts neviens ieraksts");
        return;
    }

    fetch("../API/dzest_izveleto_vesturi.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify({
            ids: ids
        })
    })
    .then(r => r.json())
    .then(data => {

        if(data.status === "success"){
            location.reload();
        }

    });

});

document.getElementById("btn-dzest-visus-pieraksti")
.addEventListener("click", () => {

    if(!confirm("Dzēst visus?")){
        return;
    }

    fetch("../API/dzest_visus_pierakstus.php", {
        method: "POST"
    })
    .then(r => r.json())
    .then(data => {

        if(data.status === "success"){
            location.reload();
        }

    });

});

document.getElementById("btn-dzest-visus-vesture")
.addEventListener("click", () => {

    if(!confirm("Dzēst visus?")){
        return;
    }

    fetch("../API/dzest_visu_vesturi.php", {
        method: "POST"
    })
    .then(r => r.json())
    .then(data => {

        if(data.status === "success"){
            location.reload();
        }

    });

});

function loadPieraksti() {
    

    fetch("../API/pieraksti.php")
    .then(response => response.json())
    .then(data => { 

        //console.log("PIERAKSTI:", data);

        let html = "";

        data.forEach(row => {

            html += `
            <tr class="pieraksts-row" data-id="${row.id}">
                
                <td>${row.atslega}</td>
                <td>${row.lietotajs}</td>
                <td>${row.start_laiks}</td>
                <td>${row.beigu_laiks}</td>
            </tr>
            `;
        });

        document.getElementById("pieraksti-dati").innerHTML = html;
    })
    .catch(error => console.error(error));
}

loadPieraksti();

document.addEventListener("click", function (e) {

    const row = e.target.closest(".pieraksts-row");
    if (!row) return;

    row.classList.toggle("selected");
});

function loadVesture() {

    fetch("../API/vesture.php")
    .then(response => response.json())
    .then(data => {

        let html = "";

        data.forEach(row => {

            html += `
            <tr class="vesture-row" data-id="${row.id}">
                <td>${row.darbiba}</td>
                <td>${row.veidota}</td>
            </tr>
            `;

        });

        document.getElementById("vesture-dati").innerHTML = html;

    })
    .catch(error => console.error(error));
}

loadVesture();

document.addEventListener("click", function (e) {

    const row = e.target.closest(".vesture-row");
    if (!row) return;

    row.classList.toggle("selected");
});

function loadLietotaji() {

    fetch("../API/konti.php")
    .then(response => response.json())
    .then(data => {

        //console.log("LIETOTAJI:", data);

        let html = "";

        data.forEach(row => {

            html += `
            <tr>
                <td>${row.epasts}</td>
                <td>${row.nosaukums}</td>
                <td>${row.uzvards}</td>
            </tr>
            `;
        });

        document.getElementById("lietotaji-dati").innerHTML = html;
    })
    .catch(error => console.error(error));
}

loadLietotaji();

// pievienošana gan klikšķim, gan Enter
const form = document.getElementById("kabinets-form");
form.addEventListener("submit", function(e) {
    e.preventDefault(); // novērš lapas refresh
    pievienotKabinetu();
});