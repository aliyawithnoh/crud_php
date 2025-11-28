<?php
require 'config.php';

$staff = $conn->query("SELECT * FROM staff ORDER BY full_name ASC");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $staff_id = $_POST['staff_id'];
    $day = $_POST['day'];
    $start = $_POST['start_time'];
    $end = $_POST['end_time'];

    $stmt = $conn->prepare("
        INSERT INTO staff_schedules (staff_id, day_of_week, start_time, end_time)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("isss", $staff_id, $day, $start, $end);
    $stmt->execute();

    header("Location: admin.php#schedules");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="container">

    <h3>Add Staff Schedule</h3>

    <form method="POST">

        <label>Staff</label>
        <select name="staff_id" class="form-control mb-2" required>
            <?php while ($s = $staff->fetch_assoc()): ?>
                <option value="<?= $s['id'] ?>"><?= $s['full_name'] ?></option>
            <?php endwhile; ?>
        </select>

        <label>Day of Week</label>
        <select name="day" class="form-control mb-2" required>
            <option>Monday</option>
            <option>Tuesday</option>
            <option>Wednesday</option>
            <option>Thursday</option>
            <option>Friday</option>
            <option>Saturday</option>
            <option>Sunday</option>
        </select>

        <label>Start Time</label>
        <input type="time" name="start_time" class="form-control mb-2" required>

        <label>End Time</label>
        <input type="time" name="end_time" class="form-control mb-3" required>

        <button class="btn btn-primary">Save</button>

    </form>

</div>

</body>
</html>
