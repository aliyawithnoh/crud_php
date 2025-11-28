<?php
require 'config.php';

$id = $_GET['id'];
$item = $conn->query("SELECT * FROM inventory WHERE id = $id")->fetch_assoc();

if (!$item) die("Item not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['item_name'];
    $qty = $_POST['quantity'];
    $unit = $_POST['unit'];

    $stmt = $conn->prepare("UPDATE inventory SET item_name=?, quantity=?, unit=? WHERE id=?");
    $stmt->bind_param("sisi", $name, $qty, $unit, $id);
    $stmt->execute();

    header("Location: admin.php#inventory");
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Edit Inventory</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">
    <h3>Edit Inventory Item</h3>

    <form method="POST">
        <label>Item Name</label>
        <input type="text" name="item_name" class="form-control mb-2" value="<?= $item['item_name'] ?>" required>

        <label>Quantity</label>
        <input type="number" name="quantity" class="form-control mb-2" value="<?= $item['quantity'] ?>" required>

        <label>Unit</label>
        <input type="text" name="unit" class="form-control mb-3" value="<?= $item['unit'] ?>" required>

        <button class="btn btn-primary">Save</button>
    </form>
</div>

</body>
</html>
