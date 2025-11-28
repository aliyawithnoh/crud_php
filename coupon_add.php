<?php
require 'config.php';

$success = "";
$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $description = $_POST['description'];
    $discount_type = $_POST['discount_type'];
    $discount_value = $_POST['discount_value'];
    $expiry = $_POST['expiry_date'];

    $stmt = $conn->prepare("
        INSERT INTO coupons (code, description, discount_type, discount_value, expiry_date)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("sssds", $code, $description, $discount_type, $discount_value, $expiry);

    if ($stmt->execute()) {
        header("Location: admin.php#coupons");
    } else {
        $error = "Failed to add coupon.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Coupon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="container">

    <h3>Add Coupon</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">

        <label>Code</label>
        <input type="text" name="code" class="form-control mb-2" required>

        <label>Description</label>
        <input type="text" name="description" class="form-control mb-2">

        <label>Discount Type</label>
        <select name="discount_type" class="form-control mb-2">
            <option value="percent">Percent (%)</option>
            <option value="fixed">Fixed Amount</option>
        </select>

        <label>Discount Value</label>
        <input type="number" name="discount_value" class="form-control mb-2">

        <label>Expiry Date</label>
        <input type="date" name="expiry_date" class="form-control mb-3">

        <button class="btn btn-primary">Save</button>

    </form>

</div>

</body>
</html>
