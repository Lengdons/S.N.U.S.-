<?php
session_start();

require_once '../mysql/datubaze.php';
require_once '../klases/zurnals.php';

$db = new datubaze();
$zurnals = new zurnals($db);

if(!isset($_SESSION['loma']) || $_SESSION['loma'] !== 'admin'){
    header("Location: FrontP.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="lv">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S.N.U.S. - Administratora Žurnāls</title>
    <link rel="stylesheet" href="Admin.css">
</head>
<body class="admin-body">

    <div class="admin-panelis">
        <div class="panelis-logo">
            <img src="SNUS_logo_green.png" alt="S.N.U.S. Logo">
        </div>
        
        <nav class="panelis-nav">
            <button class="admin-nav-btn active" data-target="s-kabineti">Kabineta Pārvaldība</button>
            <button class="admin-nav-btn" data-target="sistemas-lietotaji">Lietotāji</button>
            <button class="admin-nav-btn" data-target="sistemas-vesture">Vēsture</button>
            <button class="admin-nav-btn" data-target="sistemas-pieraksti">Pieraksti</button>

        </nav>

        <div class="panelis-footer">
            <a href="FrontP.php" class="btn-atgriezties">Atgriezties Galvenajā Lapā</a>
        </div>
    </div>

    <div class="admin-saturs">

        <div id="s-kabineti" class="admin-zurnals active">
            <h2>Kabineta Pārvaldība</h2>
            
            <div class="admin-lapa">
                <h3>Pievienot Jaunu Kabinetu</h3>
                <form id="kabinets-form" class="pievienot-kaste">
                    <input type="text" id="jauns-kabinets-nosaukums" class="admin-input" placeholder="Kabineta nosaukums...">
                    <button id="btn-pievienot-kab" class="btn-zals" disabled>Pievienot</button>
                </form>
            </div>

            <div class="admin-lapa">
                <h3>Esošie Kabineti</h3>
                <div id="kabinetu-saraksts" class="admin-list">
                    <!---šitos var izolēt JS scriptā------------------------------------>
                   
                    <!--------------------------------tikai nemaini nosaukumu------"btn-sarkans"---------------------------------->
                </div>
            </div>
        </div>

            <!------------------------------------------------------------------------------------------------------------------SAGLABĀT LIETOTĀJU-->
        <div id="sistemas-lietotaji" class="admin-zurnals">
            <h2>Lietotāju Pārvaldība</h2>
            
            <div class="admin-lapa">
                <h3>Izveidot Īslaicīgo Profilu</h3>
                <form id="lietotajs-form" class="pievienot-kaste">
                    <input type="email" id="jauns-epasts" class="admin-input" placeholder="E-pasts">
                    <input type="password" id="jauna-parole" class="admin-input" placeholder="Parole">
                    <input type="number" id="dienu-skaits" class="admin-input" placeholder="Dienu skaits" min="1" max="365" value="1">
                    
                    <button id="btn-pievienot-liet" class="btn-zals">Izveidot</button>
                </form>
            </div>

             <table class="pieraksti-tabula">
                <thead>
                    <tr>
                        <th>Epasts</th>
                        <th>Vards</th>
                        <th>Uzvards</th>
                        <th>Darbības</th>
                    </tr>
                </thead>
                <tbody id="lietotaji-dati">

                </tbody>
            </table>
        </div>
        <!-------------------------------------------------------------------------------------------------------------------------------------------->

        <div id="sistemas-vesture" class="admin-zurnals">

            <div class = "list-item">
                <span><h2>Sistēmas Vēsture</h2></span>
            
                <button id="btn-export-vesture" class="btn-zals">Eksportēt</button>
            </div>

            
            <!-- <div class="list-item">
                        <span>Kabinets Nr: 1</span>
                        <button class="btn-sarkans">Dzēst</button>
                    </div>
                     -->
            
            <div class="admin-lapa">
                <div class="tabula-pieraksti">
                    <button class="btn-sarkans" id="btn-dzest-visus-vesture">Dzēst Visus</button>
                    <button class="btn-sarkans" id="btn-dzest-izvele-vesture">Dzēst Izvēlētos</button>
                </div>
                <table class="vesture-table">
                    <thead>
                        <tr>
                            <!--   th = table HEADER   ---- šis nemainās, ja vien nevajag kaut ko man pielikt vai atņemt  -->
                            <th>Darbība</th>
                            <th>Laiks</th>
                        </tr>
                    </thead>
                    <!--Rekur orientejies pēc  id"vēstures-dati    -->
                    <tbody id="vesture-dati"></tbody>
                    <!-- katrs td iekš tr aizpilda vienu aili(šūnu) rindā-->
                </table>
            </div>
        </div>
        <!-- ---------------------------------------------------------------------------------------PIERAKSTI -->
<div id="sistemas-pieraksti" class="admin-zurnals">
    
    <div class="list-item">
        <span><h2>Aktuālie Pieraksti</h2></span>
        <button id="btn-export-pieraksti" class="btn-zals">Eksportēt</button>
    </div>
    
    <div class="admin-lapa">
        <div class="tabula-pieraksti">
            <button class="btn-sarkans" id="btn-dzest-visus-pieraksti">Dzēst Visus</button>
            <button class="btn-sarkans" id="btn-dzest-izvele-pieraksti">Dzēst Izvēlētos</button>
        </div>
        
        <table class="pieraksti-tabula">
            <thead>
                <tr>
                    <th>Atslēga</th>
                    <th>Lietotājs</th>
                    <th>Sākuma laiks</th>
                    <th>Beigu laiks</th>
                </tr>
            </thead>
            <tbody id="pieraksti-dati">

            </tbody>
        </table>
    </div>
    
</div>

    </div>
<script src="admin.js"></script>

</body>
</html>