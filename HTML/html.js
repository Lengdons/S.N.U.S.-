const sodien = new Date();
const datumi = document.querySelector(".date-box")

datumi.textContent= sodien.getDate()+"."+(sodien.getMonth()+1)+"."+sodien.getFullYear();
