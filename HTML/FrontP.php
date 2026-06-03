<?php
session_start();
$isLoggedIn = isset($_SESSION['lietotajs']);
require '../mysql/datubaze.php';
require '../klases/zurnals.php';

if(isset($_POST['save_profile'])){
    $vards = trim($_POST['vards']);
    $uzvards = trim($_POST['uzvards']);

    if($vards && $uzvards){
        $stmt = $db->conn->prepare(
            "UPDATE lietotaji SET vards=?, uzvards=? WHERE id=?"
        );

        $stmt->bind_param(
            "ssi",
            $vards,
            $uzvards,
            $_SESSION['lietotajs_id']
        );

        $stmt->execute();

        $zurnals->add(
            $vards . " " . $uzvards . " has joined the system"
        );

        header("Location: sakumlapa.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="lv">
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.N.U.S - Saņemšanas & Nodošanas Uzskaites Sistēma</title>
    <link rel="stylesheet" href="FrontP.css">
</head>
<body data-logged-in="<?= $isLoggedIn ? 'true' : 'false' ?>">
    <div class="container">
       
        <header>
            
        <div class="info-bar">
                 <div class = "logo">
                    <img src="SNUS_logo_green.png" alt="logotips">
                 </div>
            <div class="nav-nosaukums">
                 <p>SAŅEMŠANAS & NODOŠANAS UZSKAITES SISTĒMA</p>
            </div>
    
            <div class="nav-menu">
                    <input type="text" id="calendar" hidden><div id= "btn-kalendars" class="nav-item date"> Kalendārs</div>
                    <div id = "btn-filtrs" class="nav-item">Filtrs</div>
                   <!-- <div id = "btn-login" class="nav-item btn-login">Pierakstīties</div>  -->
                   <?php if(isset($_SESSION['lietotajs'])): ?>

                        <div id="btn-logout" class="nav-item btn-login">
                            Izrakstīties
                        </div>

                   <?php else: ?>

                        <div id="btn-login" class="nav-item btn-login">
                            Pierakstīties
                        </div>

                    <?php endif; ?>
            </div>
        </div>
            
        </header>
        
        <div class="search-bar">
            
            <div class = "search" ><input type="text" id="searchInput" placeholder="Meklēt">  </div>
                <div clas="search-item">
            <div class="diena-bar"> 
                <div class = "BnF prev" > &#129032 </div>
                <div class = "diena"> Pirmdiena </div>
                <div class = "BnF next" > &#129034 </div>
            </div>
            <?php if($_SESSION['loma'] === 'admin'): ?>
            <button id="btn-admin" class="btn-admin">Administrācija</button>
            <?php endif; ?>
        </div>
                

        </div>
            <div class="table-header">
                <div class="header-box">Kabinets Nr:</div>
                <div class="header-box">Vietas kabinetā:</div>
                <div class="header-box">Lietotājs</div>
                <div class="header-box">Paņēma:</div>
                <div class="header-status">Pieejamība</div>
                <div class="header-box">Nodeva:</div>
            </div>
      
<!--   Kabinets/Liet./Take./bool/Return js  -->
        <div id="datu-kaste" class="records-list"></div>

        

        </div>
         <!-- MODAL popup logs------------------------FILTRAM----------->

    <div id="filtrs-modal" class="modal-parklajums">
        
        <div class="filtrs-modal-content">
            <button id="btn-close-modal" class="filtrs-close"> &#10005 </button>      <!--Aizvēršanas poga-->
            <h2>Filtra Iestatījumi</h2>
            <p>Izvēlies filtrus: </p>
            <div class = "filtra-kaste">
                <div class = "pieejamiba" > Pieejamība </div>
                    <div class = "pieejamiba-row">
                        <button id = "btn-filtrs-pieejams" class = "filtrs-pieejams"> Pieejams </button>   <!-- Filtra poga "Pieejams"-->
                        <button id = "btn-filtrs-aiznemts" class = "filtrs-aiznemts"> Aizņemts </button>   <!-- Filtra poga "Aizņemts"-->
                     </div>
                    <div class = kabinets >Kabineta Numurs </div>
                <!-- <button id = "btn-filtrs-kabinets"class = "btn-kab-search"> --><input type="text" id="filtrs-kabinets" class="btn-kab-search" placeholder="Kabineta Numurs"><!--</button> -->
                 <!-- seit ir problema ka button box aizmugure radas un search radas prieksa, ja var tad japarlabo ari dizains pasam input -->
                </div>  
                <button id="btn-filtrs-apply" class="filtrs-apply"> Pielietot filtrus </button>      <!--Pielietot filtrus poga-->

            </div>
        </div>
        
    </div>
    
    <div id="login-modal" class="modal-parklajums login-parklajums">
        <div class="filtrs-modal-content login-content">
        
        <h2>Pierakstīties</h2>
        

        <div class="filtra-kaste login-kaste">
            <p class ="e-pasts">e-pasts</p>
            <input type="text" id="login-epasts" class="login-input" placeholder="e-pasts" required>
            <p class="parole">Parole</p>
            <input type="password" id="login-password" class="login-input" placeholder="Parole" required>
            
            <div id="login-error-msg" class="error-text"></div>
            
            <button id="btn-submit-login" class="filtrs-apply btn-login-submit">Ienākt</button>
        </div>

            <div class="registreties-link-kaste">
             <span>Nav profila?</span>
             <a href="Registreties.html" class="registreties-link">Reģistrēties šeit</a>
            </div>

        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="filtra_loga_scripts.js"></script>
<script src="FrontP.js"></script>

<script>
const btnAdmin = document.getElementById("btn-admin");

if(btnAdmin){
    btnAdmin.addEventListener("click", () => {
        window.location.href = "Admin.php";
    });
}
</script>
</body>
</html>