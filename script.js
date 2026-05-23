function openPopup(id) {
    document.getElementById('room_id').value = id;
    document.getElementById('pop').style.display = 'block';
}

const input = document.getElementById("room_name");
const button = document.getElementById("add_btn");

input.addEventListener("input", () => {
    btn.disabled = input.value.trim() === "";
});

function openPopup(id){
    document.getElementById('room_id').value = id;
    document.getElementById('overlay').style.display = 'flex';
}

function closePopup(){
    document.getElementById('overlay').style.display = 'none';
}