<?php
require 'config.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $caption = $_POST['caption'];

    $file = $_FILES['image'];
    $path = "uploads/" . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $path)) {
        $stmt = $conn->prepare("INSERT INTO gallery (image_path, caption) VALUES (?, ?)");
        $stmt->bind_param("ss", $path, $caption);
        $stmt->execute();

        header("Location: admin.php#gallery");
    } else {
        $error = "Upload failed!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Image</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="p-4">
<div class="container">

    <h3>Add Photo</h3>

    <?php if ($error): ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <label>Choose Image</label>
        <input type="file" name="image" class="form-control mb-2" required>

        <label>Caption</label>
        <input type="text" name="caption" class="form-control mb-3">

        <button class="btn btn-primary">Upload</button>
    </form>

</div>
</body>
</html>
