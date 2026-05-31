<?php

session_start();

require '../mysql/database.php';
require '../classes/booking.php';

$db = new database();
$booking = new booking($db);

if(!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin'){
    header("Location: ../index.php");
    exit;
}

if(isset($_POST['delete_selected']) && !empty($_POST['booking_ids'])){

    $ids = $_POST['booking_ids'];

    $placeholders = implode(',', array_fill(0, count($ids), '?'));

    $types = str_repeat('i', count($ids));

    $stmt = $db->conn->prepare("
        DELETE FROM bookings
        WHERE id IN ($placeholders)
    ");

    $stmt->bind_param($types, ...$ids);
    $stmt->execute();

    header("Location: bookings_page.php");
    exit;
}

if(isset($_POST['clear_bookings'])){
    $db->conn->query("DELETE FROM bookings");

    header("Location: bookings_page.php");
    exit;
}

$result = $db->conn->query("
SELECT
    bookings.id,
    rooms.name AS room_name,
    users.name,
    users.surname,
    bookings.start_time,
    bookings.end_time
FROM bookings
JOIN rooms ON rooms.id = bookings.room_id
JOIN users ON users.id = bookings.user_id
ORDER BY bookings.id DESC
");

?>

<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="../style.css">
        <script src="../script.js"></script>
    </head>
<body>

<div>
    <a href="../home/main.php" class="nav-btn">Rooms</a>
</div>

<tr>
    <td>
        <form method="POST">


    <button name="clear_bookings"
        class="danger-btn"
        onclick="return confirm('Delete ALL bookings?');">
    Clear ALL
    </button>

    <button name="delete_selected" class="danger-btn">
        Delete Selected
    </button>
    
    <a href="../export/export_bookings.php" class="nav-btn">
    Download Bookings CSV
    </a>

    <table class="logs-table">

        <thead>
            <tr>
                <th>Room</th>
                <th>User</th>
                <th>Start</th>
                <th>End</th>
            </tr>
        </thead>

        <tbody>

        <?php while($row = $result->fetch_assoc()): ?>

        <tr onclick="toggleRow(this, 'booking_ids[]')">

            <td>
                <?php echo htmlspecialchars($row['room_name'] ?? '[Deleted Room]'); ?>

                <input
                    type="hidden"
                    name="booking_ids[]"
                    value="<?php echo $row['id']; ?>"
                    disabled>
            </td>

            <td><?php echo htmlspecialchars($row['name'].' '.$row['surname']); ?></td>
            <td><?php echo $row['start_time']; ?></td>
            <td><?php echo $row['end_time']; ?></td>

        </tr>

        <?php endwhile; ?>

        </tbody>

    </table>

</form>
    </td>

</tr>

</body>
</html>