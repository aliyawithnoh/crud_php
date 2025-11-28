<?php
require 'config.php';

$id = $_GET['id'];
$schedule = $conn->query("SELECT * FROM staff_schedules WHERE id=$id")->fetch_assoc();

$staff = $conn->query("SELECT * FROM staff ORDER BY full_name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $day = $_POST['day_of_week'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];

    $stmt = $conn->prepare("
        UPDATE staff_schedules
        SET staff_id=?, day_of_week=?, start_time=?, end_time=?
        WHERE id=?
    ");
    $stmt->bind_param("isssi", $staff_id, $day, $start, $end, $id);
    $stmt->execute();

    header("Location: admin.php#schedules");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Schedule</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">

<h3>Edit Staff Schedule</h3>

<form method="POST">

    <label>Staff</label>
    <select name="staff_id" class="form-control mb-2" required>
        <?php while ($s = $staff->fetch_assoc()): ?>
            <option value="<?= $s['id'] ?>" <?= $s['id']==$schedule['staff_id']?'selected':'' ?>>
                <?= $s['full_name'] ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Day of Week</label>
    <select name="day_of_week" class="form-control mb-2">
        <?php
        $days = ['Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday'];
        foreach ($days as $d): ?>
            <option <?= $schedule['day_of_week']==$d?'selected':'' ?>><?= $d ?></option>
        <?php endforeach; ?>
    </select>

    <label>Start Time</label>
    <input type="time" name="start_time" class="form-control mb-2" value="<?= $schedule['start_time'] ?>">

    <label>End Time</label>
    <input type="time" name="end_time" class="form-control mb-3" value="<?= $schedule['end_time'] ?>">

    <button class="btn btn-primary">Save Changes</button>

</form>

</div>

</body>
</html>
