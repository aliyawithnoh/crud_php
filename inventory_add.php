<?php
require 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item = $_POST['item_name'];
    $qty = $_POST['quantity'];
    $unit = $_POST['unit'];

    $stmt = $conn->prepare("
        INSERT INTO inventory (item_name, quantity, unit) 
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sis", $item, $qty, $unit);
    $stmt->execute();

    header("Location: admin.php#inventory");
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Inventory Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">

<div class="container">
    <h3>Add Inventory Item</h3>

    <form method="POST">

        <label>Item Name</label>
        <input type="text" name="item_name" class="form-control mb-2" required>

        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control mb-2" required>

        <label>Unit (e.g., bottles, pcs)</label>
        <input type="text" name="unit" class="form-control mb-3" required>

        <button class="btn btn-primary">Save</button>
    </form>

</div>

</body>
</html>
