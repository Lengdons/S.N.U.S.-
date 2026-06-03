<?php
session_start();

if(!isset($_SESSION['lietotajs'])){
    header("Location: ../index.php");
    exit;
}

require '../mysql/datubaze.php';

$db = new datubaze();

require '../klases/atslega.php';
require '../klases/rezerve.php';
require '../klases/zurnals.php';
require '../klases/lietotajs.php';
require '../login/auth_parbaude.php';

$atslega = new atslega($db);
$rezerve = new rezerve($db);
$zurnals = new zurnals($db);
$lietotajs = new lietotajs($db);

$msg = "";

date_default_timezone_set('Europe/Riga');


// LOAD lietotajs DATA
$stmt = $db->conn->prepare("SELECT vards,uzvards FROM lietotaji WHERE id=?");
$stmt->bind_param("i", $_SESSION['lietotajs_id']);
$stmt->execute();
$lietotajsData = $stmt->get_result()->fetch_assoc();

$_SESSION['vards'] = $lietotajsData['vards'];
$_SESSION['uzvards'] = $lietotajsData['uzvards'];

$needsProfile = empty($lietotajsData['vards']) || empty($lietotajsData['uzvards']);

// SAVE PROFILE
if(isset($_POST['save_profile'])){
    $vards = trim($_POST['vards']);
    $uzvards = trim($_POST['uzvards']);

    if($vards && $uzvards){
        $stmt = $db->conn->prepare("UPDATE lietotaji SET vards=?, uzvards=? WHERE id=?");
        $stmt->bind_param("ssi", $vards, $uzvards, $_SESSION['lietotajs_id']);
        $stmt->execute();

        $zurnals->add($vards." ".$uzvards." has joined the system");

        header("Location: sakumlapa.php");
        exit;
    } else {
        $msg = "Fill all fields";
    }
}

// ADD atslega (ADMIN)
if(isset($_POST['add_atslega'])){
    if($_SESSION['loma'] !== 'admin') die("No permission");

    $nosaukums = trim($_POST['atslega_nosaukums']);
    $nosaukums = ucwords(strtolower($nosaukums));

    if($nosaukums === ""){
        $msg = "atslega nosaukums cannot be empty";
    } elseif($atslega->exists($nosaukums)){
        $msg = "atslega already exists";
    } else {
        $atslega->add($nosaukums);

        $zurnals->add($_SESSION['nosaukums']." ".$_SESSION['uzvards']." added atslega: ". $nosaukums);
        header("Location: sakumlapa.php");
        exit;
    }
}

// DELETE atslega (ADMIN)
if(isset($_POST['delete_atslega'])){
    if($_SESSION['loma'] !== 'admin') die("No permission");

    $stmt = $db->conn->prepare("SELECT nosaukums FROM atslegas WHERE id = ?");
    $stmt->bind_param("i", $_POST['delete_atslega_id']);
    $stmt->execute();

    $atslegaData = $stmt->get_result()->fetch_assoc();

    $atslega->delete($_POST['delete_atslega_id']);

    $zurnals->add($_SESSION['vards']." ".$_SESSION['uzvards']." removed atslega: ". $atslegaData['nosaukums']);
    header("Location: sakumlapa.php");
    exit;
}



if (isset($_POST['book'])) {

    if ($needsProfile) {
        $msg = "Complete your profile first.";
    } else {

        $start_date = $_POST['start_date'] ?? null;
        $end_date   = $_POST['end_date'] ?? null;

        $start_laiks = $_POST['start_laiks'] ?? null;
        $beigu_laiks   = $_POST['beigu_laiks'] ?? null;

        if (!$start_date || !$end_date || !$start_laiks || !$beigu_laiks) {
            $msg = "Invalid time selection";
        } else {

        $booklietotajsId = $_SESSION['lietotajs_id'];

        if ($_SESSION['loma'] === 'admin') {
            $booklietotajsId = $_POST['book_lietotajs_id'] ?? null;
            if (!$booklietotajsId) {
                $msg = "Select a lietotajs";
                return;
            }
        }

        if (!$msg) {
        
        $start = $start_date . " " . $start_laiks . ":00";
        $end   = $end_date . " " . $beigu_laiks . ":00";

        if (strtotime($start) >= strtotime($end)) {
            $msg = "End time must be after start time";
        }
        elseif (strtotime($start) < time()-15*60) {
            $msg = "Cannot book past time";
        }
        else {

            if ($rezerve->isAvailable($_POST['atslega_id'], $start, $end)) {

                $rezerve->book($booklietotajsId, $_POST['atslega_id'], $start, $end);

                $stmt = $db->conn->prepare("SELECT vards,uzvards FROM lietotaji WHERE id=?");
                $stmt->bind_param("i", $booklietotajsId);
                $stmt->execute();
                $u = $stmt->get_result()->fetch_assoc();

                $stmt = $db->conn->prepare("SELECT nosaukums FROM atslegas WHERE id=?");
                $stmt->bind_param("i", $_POST['atslega_id']);
                $stmt->execute();
                $atslegaData = $stmt->get_result()->fetch_assoc();

                $actor = $_SESSION['vards'] . " " . $_SESSION['uzvards'];

                if ($_SESSION['loma'] === 'admin' && $booklietotajsId != $_SESSION['lietotajs_id']) {
                    $zurnals->add(
                        $actor .
                        " booked " . $atslegaData['nosaukums'] .
                        " for lietotajs " . $u['vards'] . " " . $u['uzvards'] .
                        " from " . $start . " - " . $end
                    );
                } else {
                    $zurnals->add(
                        $u['vards'] . " " . $u['uzvards'] .
                        " booked " . $atslegaData['nosaukums'] .
                        " from " . $start . " - " . $end
                    );
                }

                $msg = "atslega booked successfully";

            } else {
                $msg = "atslega already booked in that range";
            }
        }
    }
}
    }
}


function getBookedSlots($db, $atslega_id, $date){
    $booked = [];

    $dayStart = strtotime($date . " 00:00:00");
    $dayEnd   = strtotime($date . " 23:59:59");

    $stmt = $db->conn->prepare("
        SELECT start_laiks, beigu_laiks
        FROM raksti
        WHERE atslega_id = ?
        AND start_laiks <= ?
        AND beigu_laiks >= ?
    ");

    $endDateTime   = date('Y-m-d H:i:s', $dayEnd);
    $startDateTime = date('Y-m-d H:i:s', $dayStart);

    $stmt->bind_param("iss", $atslega_id, $endDateTime, $startDateTime);
    $stmt->execute();
    $res = $stmt->get_result();

    while($row = $res->fetch_assoc()){
        $start = strtotime($row['start_laiks']);
        $end = strtotime($row['beigu_laiks']);

        $start = max($start, $dayStart);
        $end   = min($end, $dayEnd);

        while($start < $end){
            $booked[] = date("H:i", $start);
            $start = strtotime("+30 minutes", $start);
        }
    }

    return array_unique($booked);
}

function getatslegastatus($db, $atslega_id){

    $now = date('Y-m-d H:i:s');

    $stmt = $db->conn->prepare(" SELECT raksti.beigu_laiks, lietotaji.vards, lietotaji.uzvards 
        FROM raksti JOIN lietotaji on lietotaji.id = raksti.lietotajs_id WHERE atslega_id = ?
        AND start_laiks <= ?
        AND beigu_laiks > ?
        ORDER BY beigu_laiks ASC
        LIMIT 1
    ");

    $stmt->bind_param("iss", $atslega_id, $now, $now);
    $stmt->execute();

    $res = $stmt->get_result();

    if($row = $res->fetch_assoc()){

        return ['occupied' => true, 'until' => $row['beigu_laiks'], 'lietotajs' => $row['vards'].' '.$row['uzvards']];
    }

    return ['occupied' => false, 'until' => null, 'lietotajs' => null];
}

if (isset($_POST['create_lietotajs'])) {

    if ($_SESSION['loma'] !== 'admin') {
        die("No permission");
    }

    $epasts = $_POST['new_epasts'];
    $parole = $_POST['new_parole'];

    $durationDays = (int) $_POST['duration_days']; // e.g. 7, 30, etc.

    if ($durationDays > 365) $durationDays = 365;
    if ($durationDays < 1) $durationDays = 1;

    $expiresAt = date('Y-m-d H:i:s', strtotime("+$durationDays days"));

    $result = $lietotajs->registreties($epasts, $parole, $expiresAt);

    if ($result === true) {
        $zurnals->add($_SESSION['vards']." created lietotajs: " . $epasts);
        $msg = "lietotajs created successfully";
    } else {
        $msg = $result;
    }
}

//delete ur account
if(isset($_POST['delete_my_account'])){

    $stmt = $db->conn->prepare("
        UPDATE lietotaji
        SET aktivs = 0
        WHERE id = ?
    ");

    $stmt->bind_param("i", $_SESSION['lietotajs_id']);
    $stmt->execute();

    $zurnals->add($_SESSION['vards']." ".$_SESSION['uzvards']." is no longer amongus");

    session_unset();
    session_destroy();
    
    header("Location: ../index.php");
    exit;
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../style.css">
    <script src="../script.js"></script>
</head>
<body>


<div id="overlay" class="overlay" onclick="closePopup()">
    <div class="popup" onclick="event.stopPropagation()">

        <form method="POST">

            <input type="hidden" name="atslega_id" id="atslega_id">

            <?php if($_SESSION['loma'] === 'admin'): ?>

            <label>Rezervēt priekš lietotāja:</label>
            <select name="book_lietotajs_id" required>

            <?php
                $lietotaji = $db->conn->query("
                    SELECT id,vards,uzvards,epasts
                    FROM lietotaji WHERE aktivs = 1
                    ORDER BY vards ASC
                ");

                while($u = $lietotaji->fetch_assoc()):
                ?>

                    <option value="<?php echo $u['id']; ?>">
                        <?php
                        echo $u['vards'] . " " .
                            $u['uzvards'] .
                            " (" . $u['epasts'] . ")";
                        ?>
                    </option>

                <?php endwhile; ?>

            </select>

            <?php endif; ?>

            <label>Sākuma laiks:</label>
            <select name="start_laiks" required>
            <?php
            for ($h = 6; $h <= 21; $h++) {
                foreach (["00", "30"] as $m) {

                    $t = sprintf("%02d:%s", $h, $m);

                    echo "<option value='$t'>$t</option>";
                }
            }
            ?>
            </select>

            <label>Beigu laiks:</label>
            <select name="beigu_laiks" required>
            <?php
            for ($h = 6; $h <= 22; $h++) {
                foreach (["00", "30"] as $m) {

                    $t = sprintf("%02d:%s", $h, $m);

                    echo "<option value='$t'>$t</option>";
                }
            }
            ?>
            </select>

            <?php if($_SESSION['loma'] === 'admin'): ?>

                <label>Sākuma datums:</label>
                <input type="date" id="start_date" name="start_date" value="<?php echo date('Y-m-d'); ?>" required>

                <label>Beigu datums:</label>
                <input type="date" id="end_date" name="end_date" value="<?php echo date('Y-m-d'); ?>" required>
            <?php else: ?>

                <!-- regular lietotaji automatically send date by default -->
                <input type="hidden" id="start_date" name="start_date" value="<?php echo date('Y-m-d'); ?>">
                <input type="hidden" id="end_date" name="end_date" value="<?php echo date('Y-m-d'); ?>">

            <?php endif; ?>

            <button name="book">Rezervēt</button>

        </form>

        <?php if($_SESSION['loma'] === 'admin'): ?>
        <form method="POST">
            <input type="hidden" name="delete_atslega_id" id="delete_atslega_id">
            <button name="delete_atslega"
                    onclick="return confirm('Dzēst šo atslēgu?')">
                Dzēst atslēgu
            </button>
        </form>
        <?php endif; ?>

    </div>
</div>

<div id="createlietotajsOverlay" class="overlay" onclick="closeCreatelietotajsPopup()">
    <div class="popup" onclick="event.stopPropagation()">

        <form method="POST">
            <h3>Pievienot lietotāju</h3>

            <input type="email" name="new_epasts" placeholder="Epasts" required>
            <input type="password" name="new_parole" placeholder="Parole" required>
            <label>Kontu ilgums (dienās)</label>
            <input type="number" name="duration_days" value="7" min="1" max="365" oninput="this.value = Math.min(365, Math.max(1, this.value))">

            <button name="create_lietotajs">Izveidot</button>
        </form>

    </div>
</div>

<!-- PROFILE POPUP (FORCED) -->
<?php if($needsProfile): ?>
<div id="profileOverlay" class="overlay">
    <div class="popup">
        <h3>Pabeidz savu profilu</h3>
        <form method="POST">
            <input name="vards" placeholder="Vārds" required>
            <input name="uzvards" placeholder="Uzvārds" required>
            <button name="save_profile" disabled>Saglabāt</button>
        </form>
    </div>
</div>
<?php endif; ?>
<div id="app" data-needs-profile="<?php echo $needsProfile ? '1' : '0'; ?>"></div>

<div class="layout">

    <!-- LEFT SIDE -->
    <div class="sidebar">
        <h2>Sveiks, <?php echo $_SESSION['vards'] ?: $_SESSION['lietotajs']; ?></h2>

        <?php if($_SESSION['loma'] === 'admin'): ?>
            <a href="../admin/vesture.php" class="nav-btn">Žurnāli</a>
            <a href="../admin/konti.php" class="nav-btn">Konti</a>
            <a href="../admin/pieraksti.php" class="nav-btn">Vēsture</a>
        <?php endif; ?>

        <?php if($_SESSION['loma'] === 'admin'): ?>
        <form method="POST">
            <input id="atslega_nosaukums" name="atslega_nosaukums" placeholder="Jaunu atslēgu">
            <button id="add_btn" name="add_atslega" disabled>Pievienot atslēgu</button>
            <button type="button" onclick="openCreatelietotajsPopup()">Izveidot Vieša Kontu</button>
        </form>
        <?php endif; ?>
                <a href="../login/atslegties.php" class="nav-btn atslegties">Atslegties</a>
        <form method="POST" onsubmit="return confirm('Are you sure you want to deactivate your account?');">
            <button name="delete_my_account"
                    class="danger-btn">
                Dzēst manu kontu
            </button>

        </form>
        
    </div>

    <!-- RIGHT SIDE -->
    <div class="content">
        <?php if($msg): ?>
        <div class="msg"><?php echo $msg; ?></div>
        <?php endif; ?>

        <div class="atslegas">
            <?php 
            $result = $atslega->getAll();

            while($r = $result->fetch_assoc()):
                $status = getatslegastatus($db, $r['id']);
            ?>
                <div class="atslega <?php echo $needsProfile ? 'locked' : ''; ?>"
                    <?php if(!$needsProfile): ?>
                        onclick='openPopup(<?php echo $r["id"]; ?>)'
                    <?php endif; ?>
                >

                    <?php echo $r['nosaukums']; ?>

                    <?php if($status['occupied']): ?>
                        <span class="busy">
                            Aizņemts:
                            <?php echo htmlspecialchars($status['lietotajs']); ?>
                            Līdz
                            <?php echo date('H:i', strtotime($status['until'])); ?>
                        </span>
                    <?php else: ?>
                        <span class="free">
                            Pieejams
                        </span>
                    <?php endif; ?>
                </div>
            <?php endwhile; ?>
        </div>
    </div>

</div>

</body>
</html>