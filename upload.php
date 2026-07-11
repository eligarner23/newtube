<?php
session_start();

// Must be logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

// CONNECT TO DATABASE
$conn = new mysqli("localhost", "root", "newtube123", "newtube");
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Handle upload
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $title = trim($_POST["title"]);
    $user_id = $_SESSION["user_id"];

    if ($title === "") {
        $error = "Title is required.";
    } elseif (!isset($_FILES["video"]) || $_FILES["video"]["error"] !== 0) {
        $error = "You must upload a video file.";
    } else {
        // Create upload folders if missing
        if (!is_dir("uploads")) mkdir("uploads");
        if (!is_dir("thumbnails")) mkdir("thumbnails");

        // VIDEO UPLOAD
        $videoName = time() . "_" . basename($_FILES["video"]["name"]);
        $videoPath = "uploads/" . $videoName;
        move_uploaded_file($_FILES["video"]["tmp_name"], $videoPath);

        // THUMBNAIL UPLOAD (optional)
        if (!empty($_FILES["thumb"]["name"])) {
            $thumbName = time() . "_" . basename($_FILES["thumb"]["name"]);
            $thumbPath = "thumbnails/" . $thumbName;
            move_uploaded_file($_FILES["thumb"]["tmp_name"], $thumbPath);
        } else {
            $thumbName = "default_thumb.png";
        }

        // Insert into database
        $stmt = $conn->prepare("INSERT INTO videos (user_id, title, filename, thumbnail) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $user_id, $title, $videoName, $thumbName);
        $stmt->execute();

        // Redirect to channel
        header("Location: channel.php?u=" . urlencode($_SESSION["username"]));
        exit;
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Upload Video - NewTube</title>
    <style>
        body { font-family: Arial; background: #f0f0f0; padding: 40px; }
        .box {
            width: 400px; margin: auto; background: white; padding: 20px;
            border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        input, textarea { width: 100%; padding: 10px; margin: 8px 0; }
        button { width: 100%; padding: 10px; background: red; color: white; border: none; cursor: pointer; }
        button:hover { background: darkred; }
        .error { color: red; margin-bottom: 10px; }
    </style>
</head>
<body>

<div class="box">
    <h2>Upload Video</h2>

    <?php if (!empty($error)) echo "<div class='error'>$error</div>"; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Video Title">
        <label>Video File:</label>
        <input type="file" name="video" accept="video/*">

        <label>Thumbnail (optional):</label>
        <input type="file" name="thumb" accept="image/*">

        <button type="submit">Upload</button>
    </form>
</div>

</body>
</html>
