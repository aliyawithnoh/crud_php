<?php
session_start();
require 'config.php';

// Block access if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Fetch bookings (joined with services)
$bookings = $conn->query("
    SELECT b.id, b.customer_name, b.booking_date, b.booking_time,
           s.name AS service_name, s.price
    FROM bookings b
    JOIN services s ON b.service_id = s.id
    ORDER BY b.booking_date DESC, b.booking_time DESC
");

// Fetch services
$services = $conn->query("SELECT * FROM services ORDER BY name ASC");

// Fetch users
$users = $conn->query("SELECT id, username, role FROM users ORDER BY username ASC");

$reviews = $conn->query("
    SELECT r.*, u.username, b.customer_name
    FROM reviews r
    JOIN users u ON r.user_id = u.id
    JOIN bookings b ON r.booking_id = b.id
    ORDER BY r.created_at DESC
");

$coupons = $conn->query("SELECT * FROM coupons ORDER BY created_at DESC");

$schedules = $conn->query("
    SELECT ss.*, s.full_name
    FROM staff_schedules ss
    JOIN staff s ON ss.staff_id = s.id
    ORDER BY FIELD(day_of_week,'Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday')
");

$inventory = $conn->query("SELECT * FROM inventory ORDER BY item_name ASC");

$gallery = $conn->query("SELECT * FROM gallery ORDER BY created_at DESC");


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">

<nav class="navbar navbar-dark bg-primary">
    <div class="container">
        <span class="navbar-brand">Admin Dashboard</span>

        <span class="text-white">
            Logged in as <strong><?= $_SESSION['username'] ?></strong>
            |
            <a href="logout.php" class="text-white text-decoration-underline">Logout</a>
        </span>
    </div>
</nav>

<div class="container mt-4">

    <ul class="nav nav-tabs" id="adminTabs" role="tablist">
        <li class="nav-item">
            <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#bookings" type="button">
                Bookings
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#services" type="button">
                Services
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#users" type="button">
                Users
            </button>
        </li>

        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#reviews" type="button">
                Reviews
            </button>
        </li>
        
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#coupons" type="button">
                Coupons
            </button>
        </li>
        
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#schedules" type="button">
                Staff Schedules
            </button>
        </li>
        
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#inventory" type="button">
                Inventory
            </button>
        </li>
        
        <li class="nav-item">
            <button class="nav-link" data-bs-toggle="tab" data-bs-target="#gallery" type="button">
                Gallery
            </button>
        </li>

    </ul>

    <div class="tab-content mt-3">

        <!-- ================= BOOKING TAB ================= -->
        <div class="tab-pane fade show active" id="bookings">

            <div class="d-flex justify-content-between mb-2">
                <h4>All Bookings</h4>
                <a href="booking_add.php" class="btn btn-primary btn-sm">Add Booking</a>
            </div>

            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Service</th>
                                <th>Price</th>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($row = $bookings->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['customer_name'] ?></td>
                                    <td><?= $row['service_name'] ?></td>
                                    <td>₱<?= number_format($row['price']) ?></td>
                                    <td><?= $row['booking_date'] ?></td>
                                    <td><?= $row['booking_time'] ?></td>
                                    <td>
                                        <a href="booking_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="booking_delete.php?id=<?= $row['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Delete this booking?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>
                </div>
            </div>

        </div>

        <!-- ================= SERVICES TAB ================= -->
        <div class="tab-pane fade" id="services">
            
            <div class="d-flex justify-content-between mb-2">
                <h4>Services</h4>
                <a href="service_add.php" class="btn btn-primary btn-sm">Add Service</a>
            </div>

            <div class="card">
                <div class="card-body table-responsive">

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Service Name</th>
                                <th>Price</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($row = $services->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['name'] ?></td>
                                    <td>₱<?= number_format($row['price']) ?></td>
                                    <td>
                                        <a href="service_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="service_delete.php?id=<?= $row['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Delete this service?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>

                </div>
            </div>

        </div>

        <!-- ================= USERS TAB ================= -->
        <div class="tab-pane fade" id="users">
            
            <div class="d-flex justify-content-between mb-2">
                <h4>User Accounts</h4>
                <a href="user_add.php" class="btn btn-primary btn-sm">Add User</a>
            </div>

            <div class="card">
                <div class="card-body table-responsive">

                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Username</th>
                                <th>Role</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($row = $users->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['username'] ?></td>
                                    <td><?= ucfirst($row['role']) ?></td>
                                    <td>
                                        <a href="user_edit.php?id=<?= $row['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                                        <a href="user_delete.php?id=<?= $row['id'] ?>" 
                                           class="btn btn-sm btn-danger"
                                           onclick="return confirm('Delete this user?')">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>

                </div>
            </div>

        </div>
        <div class="tab-pane fade" id="reviews">
            <h4>Customer Reviews</h4>

            <div class="card">
                <div class="card-body table-responsive">

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Customer</th>
                                <th>Rating</th>
                                <th>Review</th>
                                <th>Date</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($row = $reviews->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['id'] ?></td>
                                    <td><?= $row['username'] ?></td>
                                    <td><?= $row['customer_name'] ?></td>
                                    <td><?= $row['rating'] ?>/5</td>
                                    <td><?= $row['review_text'] ?></td>
                                    <td><?= $row['created_at'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="coupons">
            <div class="d-flex justify-content-between mb-2">
                <h4>Coupons</h4>
                <a href="coupon_add.php" class="btn btn-primary btn-sm">Add Coupon</a>
            </div>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Code</th>
                                <th>Type</th>
                                <th>Value</th>
                                <th>Expiry</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($c = $coupons->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $c['code'] ?></td>
                                    <td><?= $c['discount_type'] ?></td>
                                    <td><?= $c['discount_value'] ?></td>
                                    <td><?= $c['expiry_date'] ?></td>
                                    <td><?= $c['is_active'] ? "Active" : "Inactive" ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>  
                    </table>
                </div>
            </div>
        </div>

        <div class="tab-pane fade" id="schedules">
            <h4>Staff Weekly Schedules</h4>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Staff</th>
                                <th>Day</th>
                                <th>Start</th>
                                <th>End</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $schedules->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['full_name'] ?></td>
                                    <td><?= $row['day_of_week'] ?></td>
                                    <td><?= $row['start_time'] ?></td>
                                    <td><?= $row['end_time'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>                    
                    </table>       
                </div>
            </div>        
        </div>


        <div class="tab-pane fade" id="inventory">                                    
            <h4>Inventory</h4>
            <div class="card">
                <div class="card-body table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Item</th>
                                <th>Quantity</th>
                                <th>Unit</th>
                                <th>Updated</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $inventory->fetch_assoc()): ?>
                                <tr>
                                    <td><?= $row['item_name'] ?></td>
                                    <td><?= $row['quantity'] ?></td>
                                    <td><?= $row['unit'] ?></td>
                                    <td><?= $row['last_updated'] ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>                         
                    </table>                          
                </div>
            </div>                 
        </div>

        <div class="tab-pane fade" id="gallery">

            <div class="d-flex justify-content-between mb-3">
                <h4>Gallery</h4>
                <a href="gallery_add.php" class="btn btn-primary btn-sm">Add Photo</a>
            </div>

            <div class="row g-3">
                <?php while ($g = $gallery->fetch_assoc()): ?>
                <div class="col-md-3">
                    <div class="card shadow-sm h-100">              
                        <img src="<?= $g['image_path'] ?>" class="card-img-top" style="height:180px; object-fit:cover;">             
                        <div class="card-body">
                            <p class="text-center"><?= $g['caption'] ?></p>
                        </div>              
                        <div class="card-footer text-center bg-light">
                            <a href="gallery_edit.php?id=<?= $g['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="gallery_delete.php?id=<?= $g['id'] ?>"
                               onclick="return confirm('Delete this image?')"
                               class="btn btn-danger btn-sm">Delete</a>
                        </div>
                
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
