<?php
require 'config.php';
if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit; }

// Fetch bookings joined with user + service
$stmt = $conn->query("
    SELECT b.id, u.username, s.name, b.booking_date, b.status
    FROM bookings b
    JOIN users u ON b.user_id = u.id
    JOIN services s ON b.service_id = s.id
    ORDER BY b.booking_date DESC
");
$bookings = [];
if ($stmt && $stmt->num_rows > 0) {
    while ($row = $stmt->fetch_assoc()) {
        $bookings[] = $row;
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Bookings - Admin</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-5">

<h2>Bookings</h2>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h5>Total Bookings</h5>
                <h3><?= count($bookings) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h5>Paid</h5>
                <h3><?= array_reduce($bookings, fn($c, $b) => $c + ($b['payment_status'] === 'completed'), 0) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark">
            <div class="card-body">
                <h5>Pending</h5>
                <h3><?= array_reduce($bookings, fn($c, $b) => $c + ($b['payment_status'] === 'pending'), 0) ?></h3>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h5>Unpaid</h5>
                <h3><?= array_reduce($bookings, fn($c, $b) => $c + ($b['payment_status'] === 'unpaid'), 0) ?></h3>
            </div>
        </div>
    </div>
</div>
<a href="booking_add.php" class="btn btn-primary mb-3">Add Booking</a>
<a href="admin.php" class="btn btn-secondary mb-3">Back to Dashboard</a>

<table class="table table-bordered table-striped">
    <thead class="table-dark">
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Service</th>
            <th>Date</th>
            <th>Status</th>
            <th width="160">Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($bookings as $b): ?>
        <tr>
            <td><?= $b['id'] ?></td>
            <td><?= htmlspecialchars($b['username']) ?></td>
            <td><?= htmlspecialchars($b['name']) ?></td>
            <td><?= $b['booking_date'] ?></td>
            <td><?= $b['status'] ?></td>
            <td>
                <a href="booking_edit.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                <a href="booking_delete.php?id=<?= $b['id'] ?>" class="btn btn-sm btn-danger"
                   onclick="return confirm('Delete this booking?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

</body>
</html>
