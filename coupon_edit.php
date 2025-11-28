<?php
require 'config.php';

$id = $_GET['id'];

$coupon = $conn->query("SELECT * FROM coupons WHERE id = $id")->fetch_assoc();

if (!$coupon) {
    die("Coupon not found.");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $code = $_POST['code'];
    $description = $_POST['description'];
    $discount_type = $_POST['discount_type'];
    $discount_value = $_POST['discount_value'];
    $expiry = $_POST['expiry_date'];
    $active = isset($_POST['is_active']) ? 1 : 0;

    $stmt = $conn->prepare("
        UPDATE coupons 
        SET code=?, description=?, discount_type=?, discount_value=?, expiry_date=?, is_active=?
        WHERE id=?
    ");
    $stmt->bind_param("sssdsii", $code, $description, $discount_type, $discount_value, $expiry, $active, $id);
    $stmt->execute();

    header("Location: admin.php#coupons");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Coupon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
    <h3>Edit Coupon</h3>

    <form method="POST">
        <label>Code</label>
        <input type="text" name="code" class="form-control mb-2" value="<?= $coupon['code'] ?>" required>

        <label>Description</label>
        <input type="text" name="description" class="form-control mb-2" value="<?= $coupon['description'] ?>">

        <label>Discount Type</label>
        <select name="discount_type" class="form-control mb-2">
            <option value="percent" <?= $coupon['discount_type']=='percent'?'selected':'' ?>>Percent</option>
            <option value="fixed" <?= $coupon['discount_type']=='fixed'?'selected':'' ?>>Fixed Amount</option>
        </select>

        <label>Discount Value</label>
        <input type="number" name="discount_value" class="form-control mb-2" value="<?= $coupon['discount_value'] ?>">

        <label>Expiry Date</label>
        <input type="date" name="expiry_date" class="form-control mb-2" value="<?= $coupon['expiry_date'] ?>">

        <label class="form-check mt-2">
            <input type="checkbox" class="form-check-input" name="is_active" <?= $coupon['is_active']?'checked':'' ?>>
            Active
        </label>

        <button class="btn btn-primary mt-3">Save Changes</button>
    </form>

</div>

</body>
</html>
