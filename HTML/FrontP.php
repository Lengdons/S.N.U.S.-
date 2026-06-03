<?php
session_start();
// We tell the HTML if the user is logged in or not
$isLoggedIn = isset($_SESSION['user']) ? 'true' : 'false';
?>


<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.N.U.S - Saņemšanas & Nodošanas Uzskaites Sistēma</title>
    <link rel="stylesheet" href="FrontP.css">
</head>
<body data-logged-in="<?php echo $isLoggedIn; ?>">
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
                    <div id= "btn-kalendars" class="nav-item date">Kalendārs</div>
                    <div id = "btn-filtrs" class="nav-item">Filtrs</div>
                   <div id = "btn-login" class="nav-item btn-login">Pierakstīties</div> 
            </div>
        </div>
            
        </header>
        
        <div class="search-bar">
            <div class = "search" ><input type="text" id="searchInput" placeholder="Meklēt">  </div>
                
            <div class="diena-bar"> 
                <div class = "BnF prev" > &#129032 </div>
                <div class = "diena"> Pirmdiena </div>
                <div class = "BnF next" > &#129034 </div>
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
                    <div class = kabinets > Kabineta Numurs </div>
                <button id = "btn-filtrs-kabinets"class = "btn-kab-search"> ...  </button>
                </div>  
                <button id="btn-filtrs-apply" class="filtrs-apply"> Pielietot filtrus </button>      <!--Pielietot filtrus poga-->

            </div>
        </div>
        
    </div>

    <div id="login-modal" class="modal-parklajums login-parklajums">
        <div class="filtrs-modal-content login-content">
        
        <h2>Pierakstīties</h2>
        <p class ="e-pasts">e-pasts</p>
        <p class="parole">Parole</p>

        <div class="filtra-kaste login-kaste">
            <input type="text" id="login-epasts" class="login-input" placeholder="e-pasts" required>
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

<script src="filtra_loga_scripts.js"></script>
<script src="FrontP.js"></script>

</body>
</html>