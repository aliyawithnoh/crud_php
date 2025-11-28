<?php
require 'config.php';

$services = $conn->query("SELECT id, name, price, description FROM services ORDER BY name ASC");
$gallery = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC LIMIT 6");
$success = "";
$error = "";

if (isset($_POST['submit_booking'])) {
    $customer_name = trim($_POST['customer_name']);
    $service_id = intval($_POST['service_id']);
    $booking_date = $_POST['booking_date'];
    $booking_time = $_POST['booking_time'];

    if ($customer_name && $service_id && $booking_date && $booking_time) {

        $stmt = $conn->prepare("INSERT INTO bookings (customer_name, service_id, booking_date, booking_time, status, user_id)
                                VALUES (?, ?, ?, ?, 'pending', 0)");
        $stmt->bind_param("siss", $customer_name, $service_id, $booking_date, $booking_time);

        if ($stmt->execute()) {
            $success = "Your booking has been submitted successfully!";
        } else {
            $error = "Error saving booking.";
        }

    } else {
        $error = "Please fill all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #0d0f14;
            color: #e4e4e4;
            font-family: 'Segoe UI', sans-serif;
        }
        .hero {
            background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.7)), url('hero.jpg') center/cover no-repeat;
            height: 60vh;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            color: white;
        }
        .service-card {
            background: #151821;
            border-radius: 12px;
            padding: 20px;
            transition: 0.3s;
        }
        .service-card:hover {
            transform: translateY(-5px);
            background: #1c1f2b;
        }
        .gallery-img {
            height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }
        .modal-content {
            background: #1c1f2b;
            color: #e4e4e4;
            border-radius: 12px;
        }
        .form-control, select {
            background: #0f1117;
            border: 1px solid #333;
            color: #e4e4e4;
        }
        .form-control:focus {
            border-color: #6a5acd;
            box-shadow: 0 0 0 0.2rem rgba(106,90,205,0.25);
        }
        .btn-primary {
            background: #6a5acd;
            border: none;
        }
        .btn-primary:hover {
            background: #5a4ac9;
        }
    </style>
</head>

<body>

<!-- HERO SECTION -->
<div class="hero">
    <div>
        <h1 class="display-4 fw-bold">Relax. Recharge. Rejuvenate.</h1>
        <p class="lead">Book your premium spa session instantly.</p>
        <button class="btn btn-primary btn-lg mt-3" data-bs-toggle="modal" data-bs-target="#bookingModal">Book Now</button>
    </div>
</div>

<div class="container py-5">

    <!-- SERVICES SECTION -->
    <h2 class="mb-4 text-center">Our Services</h2>
    <div class="row g-4">
        <?php while ($s = $services->fetch_assoc()): ?>
            <div class="col-md-4">
                <div class="service-card shadow-sm p-4">
                    <h4><?= $s['name'] ?></h4>
                    <p class="text-muted">₱<?= number_format($s['price']) ?></p>
                    <p><?= $s['description'] ?></p>
                    <button class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#bookingModal">
                        Book This Service
                    </button>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <!-- GALLERY SECTION -->
    <h2 class="mt-5 mb-4 text-center">Gallery</h2>
    <div class="row g-3">
        <?php while ($g = $gallery->fetch_assoc()): ?>
            <div class="col-md-4">
                <img src="<?= $g['image_path'] ?>" class="img-fluid gallery-img shadow">
            </div>
        <?php endwhile; ?>
    </div>

</div>

<!-- BOOKING MODAL -->
<div class="modal fade" id="bookingModal">
    <div class="modal-dialog">
        <div class="modal-content p-3">

            <div class="modal-header border-0">
                <h5 class="modal-title">Book a Service</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <?php if ($success): ?>
                    <div class="alert alert-success"><?= $success ?></div>
                <?php endif; ?>

                <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                <?php endif; ?>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Customer Name</label>
                        <input type="text" name="customer_name" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Select Service</label>
                        <select name="service_id" class="form-control" required>
                            <option value="">-- Choose a Service --</option>
                            <?php
                            $services2 = $conn->query("SELECT id, name, price FROM services ORDER BY name ASC");
                            while ($row = $services2->fetch_assoc()): ?>
                                <option value="<?= $row['id'] ?>">
                                    <?= $row['name'] ?> (₱<?= number_format($row['price']) ?>)
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Booking Date</label>
                        <input type="date" name="booking_date" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Booking Time</label>
                        <input type="time" name="booking_time" class="form-control" required>
                    </div>

                    <button name="submit_booking" class="btn btn-primary w-100">Submit Booking</button>

                </form>
            </div>

        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
