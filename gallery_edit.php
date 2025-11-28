<?php
require 'config.php';

$id = $_GET['id'];
$photo = $conn->query("SELECT * FROM gallery WHERE id=$id")->fetch_assoc();

if (!$photo) die("Image not found.");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = $_POST['caption'];

    // If a new image is uploaded
    if (!empty($_FILES['image']['name'])) {
        $file = $_FILES['image'];
        $path = "uploads/" . basename($file['name']);
        move_uploaded_file($file['tmp_name'], $path);

        $stmt = $conn->prepare("UPDATE gallery SET image_path=?, caption=? WHERE id=?");
        $stmt->bind_param("ssi", $path, $caption, $id);
    } else {
        $stmt = $conn->prepare("UPDATE gallery SET caption=? WHERE id=?");
        $stmt->bind_param("si", $caption, $id);
    }

    $stmt->execute();
    header("Location: admin.php#gallery");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Gallery Item</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">

<h3>Edit Gallery Item</h3>

<img src="<?= $photo['image_path'] ?>" class="img-fluid mb-3 rounded shadow" style="max-width:300px;">

<form method="POST" enctype="multipart/form-data">

    <label>Change Image (Optional)</label>
    <input type="file" name="image" class="form-control mb-2">

    <label>Caption</label>
    <input type="text" name="caption" class="form-control mb-3" value="<?= $photo['caption'] ?>">

    <button class="btn btn-primary">Save Changes</button>
</form>

</div>

</body>
</html>
