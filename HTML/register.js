document.getElementById("btn-submit-register").addEventListener("click", async () => {

    const epasts = document.getElementById("reg-epasts").value;
    const parole = document.getElementById("reg-password").value;

    const formData = new FormData();

    formData.append("epasts", epasts);
    formData.append("parole", parole);

    try {

        const response = await fetch("../API/Registreties.php", {
            method: "POST",
            body: formData
        });

        const data = await response.json();

        alert(data.message);

        if (data.status === "success") {
            window.location.href = "FrontP.php";
        }

    } catch (error) {

        console.error(error);
        alert("Servera kļūda");

    }

});