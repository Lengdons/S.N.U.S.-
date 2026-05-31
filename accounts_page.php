<?php
session_start();

require_once 'mysql/database.php';
require_once 'log.php';

$db = new database();
$log = new log($db);

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: index.php");
    exit;
}

// DELETE USER
if(isset($_POST['delete_user_id'])){
    
    $stmt = $db->conn->prepare("SELECT name, surname, email FROM users WHERE id = ?");
    $stmt->bind_param("i", $_POST['delete_user_id']);
    $stmt->execute();
    $userData = $stmt->get_result()->fetch_assoc();

    $stmt = $db->conn->prepare("UPDATE users SET is_active = 0 WHERE id = ?");
    $stmt->bind_param("i", $_POST['delete_user_id']);
    $stmt->execute();
    
    $log->add($_SESSION['name']." ".$_SESSION['surname']." deactivated user: ".$userData['name']." ".$userData['surname']);
    header("Location: accounts_page.php");
    exit;
}

// GET USERS
$result = $db->conn->query("
    SELECT id, name, surname, email
    FROM users WHERE role != 'admin' AND is_active = 1 AND role != 'temp'
    ORDER BY id DESC
");
?>

<link rel="stylesheet" href="style.css">

<h2>Accounts</h2>

<div>
    <a href="main.php" class="nav-btn">Rooms</a>
</div>

<table class="logs-table">
    <thead>
        <tr>
            <th>Name</th>
            <th>Surname</th>
            <th>Email</th>
            <th>Action</th>
        </tr>
    </thead>

    <tbody>
        <?php while($u = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($u['name']) ?></td>
            <td><?= htmlspecialchars($u['surname']) ?></td>
            <td><?= htmlspecialchars($u['email']) ?></td>

            <td>
                <form method="POST"
                      onsubmit="return confirm('Delete this account?');">
                    <input type="hidden" name="delete_user_id" value="<?= $u['id'] ?>">
                    <button class="danger-btn">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>