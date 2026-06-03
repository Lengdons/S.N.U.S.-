function deleteRoom(id){

    const formData = new FormData();
    formData.append("room_id", id);

    fetch("../API/izdzest_kabinetu.php", {
        method: "POST",
        body: formData
    })
    .then(response => response.json())
    .then(data => {

        alert(data.message);

        location.reload();

    });

}


fetch("../API/kabineti.php").then(res => res.json()).then(rooms => {
    let html = "";

    rooms.forEach(room => {

        html += `
        <div class="list-item">
            <span>Kabinets Nr: ${room.name}</span>
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

//pievienot kabinetu kas izmanto pievienot kabinetu api
//ar post palidzibu ievieto db jauno
document.getElementById("btn-pievienot-kab").addEventListener("click",()=>{
    const roomName =
        document.getElementById(
            "jauns-kabinets-nosaukums"
        ).value;

    const formData = new FormData();

    formData.append(
        "room_name",
        roomName
    );

    fetch("../API/pievienot_kabinetu.php", {

        method: "POST",
        body: formData

    })
    .then(response => response.json()).then(data => {
        alert(data.message);
    });
});

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